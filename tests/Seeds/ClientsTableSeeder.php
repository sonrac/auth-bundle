<?php

namespace sonrac\Auth\Tests\Seeds;

use sonrac\SimpleSeed\RollBackSeedWithCheckExists;

/**
 * Class Clients.
 */
class ClientsTableSeeder extends RollBackSeedWithCheckExists
{
    /**
     * {@inheritdoc}
     */
    protected function getTable(): string
    {
        return 'clients';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData(): array
    {
        return [
            [
                'name'        => 'Test Client',
                'description' => 'First test client',
                'secret'      => 'secret-key',
                'created_at'  => \time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data): array
    {
        return ['name' => $data['name']];
    }

    /**
     * {@inheritdoc}
     */
    protected function getDeleteFields($data): array
    {
        return $this->getWhereForRow($data);
    }
}
