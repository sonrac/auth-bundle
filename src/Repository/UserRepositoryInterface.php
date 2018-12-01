<?php

declare(strict_types=1);

namespace sonrac\Auth\Repository;

use sonrac\Auth\Entity\UserInterface;

/**
 * Interface UserRepositoryInterface
 * @package sonrac\Auth\Repository
 */
interface UserRepositoryInterface extends \League\OAuth2\Server\Repositories\UserRepositoryInterface
{
    /**
     * @param string|int $identifier
     *
     * @return null|\sonrac\Auth\Entity\UserInterface
     */
    public function findByIdentifier($identifier): ?UserInterface;
}
