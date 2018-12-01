<?php

declare(strict_types=1);

namespace sonrac\Auth\Repository;

use sonrac\Auth\Entity\ClientInterface;

/**
 * Interface ClientRepositoryInterface
 * @package sonrac\Auth\Repository
 */
interface ClientRepositoryInterface extends \League\OAuth2\Server\Repositories\ClientRepositoryInterface
{
    /**
     * @param string|int $identifier
     *
     * @return null|\sonrac\Auth\Entity\ClientInterface
     */
    public function findByIdentifier($identifier): ?ClientInterface;
}
