<?php

namespace sonrac\Auth\Tests\Seeds;

use sonrac\SimpleSeed\SimpleSeedWithCheckExists;

/**
 * Class Clients.
 */
class Clients extends SimpleSeedWithCheckExists
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
}
