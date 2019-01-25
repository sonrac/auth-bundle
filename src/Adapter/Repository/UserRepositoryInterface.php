<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/19/18
 * Time: 9:37 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Adapter\Repository;

use Sonrac\OAuth2\Adapter\Entity\UserEntityInterface;

/**
 * Interface UserRepositoryInterface
 * @package Sonrac\OAuth2\Adapter\Repository
 */
interface UserRepositoryInterface
{
    /**
     * @param string|int $username
     *
     * @return \Sonrac\OAuth2\Adapter\Entity\UserEntityInterface|null
     */
    public function findUserByUsername($username): ?UserEntityInterface;
}
