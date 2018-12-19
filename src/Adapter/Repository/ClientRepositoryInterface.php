<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/19/18
 * Time: 9:24 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Adapter\Repository;

use Sonrac\OAuth2\Adapter\Entity\ClientEntityInterface;

/**
 * Interface ClientRepositoryInterface
 * @package Sonrac\OAuth2\Adapter\Repository
 */
interface ClientRepositoryInterface
{
    /**
     * @param string|int $identifier
     *
     * @return \Sonrac\OAuth2\Adapter\Entity\ClientEntityInterface|null
     */
    public function findClientEntityByIdentifier($identifier): ?ClientEntityInterface;
}
