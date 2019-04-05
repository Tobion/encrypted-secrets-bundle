<?php

namespace App\SecretsEncryption\Command;

use App\SecretsEncryption\Encoding\BinaryEncoderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateKeyCommand extends Command
{
    protected static $defaultName = 'app:secrets:generate-key';

    /**
     * @var BinaryEncoderInterface
     */
    private $binaryEncoder;

    public function __construct(BinaryEncoderInterface $binaryEncoder)
    {
        $this->binaryEncoder = $binaryEncoder;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Prints a randomly generated encryption key.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $encryptionKey = sodium_crypto_stream_keygen();

        $encoded = $this->binaryEncoder->encode($encryptionKey);

        $output->writeln($encoded, OutputInterface::OUTPUT_RAW);

        sodium_memzero($encryptionKey);
        sodium_memzero($encoded);
    }
}
