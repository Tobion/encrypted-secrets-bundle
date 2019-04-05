<?php

namespace App\SecretsEncryption\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ListSecretsCommand extends Command
{
    protected static $defaultName = 'app:secrets:list';

    /**
     * @var string[]
     */
    private $decryptedSecrets = [];

    public function __construct(array $decryptedSecrets)
    {
        $this->decryptedSecrets = $decryptedSecrets;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Lists all configured secrets in decrypted form.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(['name', 'plaintext secret']);

        foreach ($this->decryptedSecrets as $name => $secret) {
            $table->addRow([$name, $secret]);
        }

        $table->render();
    }
}
