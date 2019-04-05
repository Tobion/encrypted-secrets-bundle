<?php

namespace App\SecretsEncryption\DependencyInjection;

use App\SecretsEncryption\Command\DecryptSecretsCommand;
use App\SecretsEncryption\Command\EncryptSecretsCommand;
use App\SecretsEncryption\Command\GenerateKeyCommand;
use App\SecretsEncryption\Command\ListSecretsCommand;
use App\SecretsEncryption\Encoding\BinaryEncoderInterface;
use App\SecretsEncryption\Encoding\EncoderFactory;
use App\SecretsEncryption\EncryptedMessage;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SecretsEncryptionExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $binaryEncoder = EncoderFactory::createEncoderFromName($config['encoding']);
        $container->register('secrets.encoder', \get_class($binaryEncoder));
        $encoderRef = new Reference('secrets.encoder');

        $container->register(GenerateKeyCommand::class)->setAutoconfigured(true)
            ->setArguments([$encoderRef]);

        if (\array_key_exists('encryption_key', $config)) {
            $encryptionKey = $container->resolveEnvPlaceholders($config['encryption_key'], true);
            if (!$encryptionKey) {
                throw new \RuntimeException('The encryption_key is not configured, please check the documentation to fix this issue');
            }

            $encryptionKey = $binaryEncoder->decode($encryptionKey);

            $this->decryptSecretsAsParameters($encryptionKey, $config['encrypted_secrets'], $binaryEncoder, $container);

            $container->register(DecryptSecretsCommand::class)->setAutoconfigured(true)
                ->setArguments([$encryptionKey, $encoderRef]);

            $container->register(EncryptSecretsCommand::class)->setAutoconfigured(true)
                ->setArguments([$encryptionKey, $encoderRef]);

            sodium_memzero($encryptionKey);
        }
    }

    private function decryptSecretsAsParameters(
        string $encryptionKey,
        array $encryptedSecrets,
        BinaryEncoderInterface $binaryEncoder,
        ContainerBuilder $container
    ): void {
        $secrets = [];

        foreach ($encryptedSecrets as $name => $encodedMessage) {
            $decoded = $binaryEncoder->decode($encodedMessage);
            $message = EncryptedMessage::createFromString($decoded);

            $secret = sodium_crypto_stream_xor($message->getCiphertext(), $message->getNonce(), $encryptionKey);
            $container->setParameter($name, $secret);
            $secrets[$name] = $secret;

            sodium_memzero($secret);
        }

        $container->register(ListSecretsCommand::class)->setAutoconfigured(true)
            ->setArguments([$secrets]);

        sodium_memzero($encryptionKey);
    }
}
