<?php

namespace App\SecretsEncryption\Command;

use App\SecretsEncryption\Encoding\BinaryEncoderInterface;
use App\SecretsEncryption\EncryptedMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DecryptSecretsCommand extends Command
{
    protected static $defaultName = 'app:secrets:decrypt';

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
            ->setDescription('Decrypts encrypted secrets and prints the plaintext value.')
            ->addArgument(
                'ciphertext',
                InputArgument::REQUIRED
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $encodedMessage = $input->getArgument('ciphertext');
        \assert(\is_string($encodedMessage));

        $decoded = $this->binaryEncoder->decode($encodedMessage);
        $message = EncryptedMessage::createFromString($decoded);

        $secret = sodium_crypto_stream_xor($message->getCiphertext(), $message->getNonce(), $this->encryptionKey);

        $output->writeln($secret, OutputInterface::OUTPUT_RAW);

        sodium_memzero($secret);
    }
}
