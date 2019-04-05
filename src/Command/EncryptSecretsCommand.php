<?php

namespace App\SecretsEncryption\Command;

use App\SecretsEncryption\Encoding\BinaryEncoderInterface;
use App\SecretsEncryption\EncryptedMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class EncryptSecretsCommand extends Command
{
    protected static $defaultName = 'app:secrets:encrypt';

    /**
     * @var string
     */
    private $encryptionKey;

    /**
     * @var BinaryEncoderInterface
     */
    private $binaryEncoder;

    public function __construct(string $encryptionKey, BinaryEncoderInterface $binaryEncoder)
    {
        $this->encryptionKey = $encryptionKey;
        $this->binaryEncoder = $binaryEncoder;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Encrypts a secret and prints the encrypted value in the given encoding format.')
            ->addArgument(
                'secret',
                InputArgument::REQUIRED
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $secret = $input->getArgument('secret');
        \assert(\is_string($secret));

        $nonce = random_bytes(SODIUM_CRYPTO_STREAM_NONCEBYTES);
        $ciphertext = sodium_crypto_stream_xor($secret, $nonce, $this->encryptionKey);

        sodium_memzero($secret);

        $message = new EncryptedMessage($ciphertext, $nonce);
        $encoded = $this->binaryEncoder->encode((string) $message);

        $output->writeln($encoded, OutputInterface::OUTPUT_RAW);
    }
}
