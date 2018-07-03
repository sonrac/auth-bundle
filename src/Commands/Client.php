<?php

namespace sonrac\Auth\Commands;

use sonrac\Auth\Entity\Client as ClientEntity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Client.
 */
class Client extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->addOption(
            'name',
            'n',
            InputOption::VALUE_REQUIRED,
            'Client application name'
        )->addOption(
            'scopes',
            's',
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
            'Client scopes',
            [
                'default',
            ]
        )->addOption(
            'description',
            'd',
            InputOption::VALUE_OPTIONAL,
            'Client application description',
            'Created by console command'
        )->addOption(
            'grant-types',
            'g',
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
            'Allowed grant types. By default include all implement grant types',
            [
                ClientEntity::GRANT_CLIENT_CREDENTIALS,
                ClientEntity::GRANT_PASSWORD,
                ClientEntity::GRANT_AUTH_CODE,
                ClientEntity::GRANT_IMPLICIT,
            ]
        );
    }
}
