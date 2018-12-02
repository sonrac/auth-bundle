<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/2/18
 * Time: 3:32 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Adapter\League\Repository;

use Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface;

/**
 * Interface ClientRepositoryInterface
 * @package Sonrac\OAuth2\Adapter\League\Repository
 */
interface ClientRepositoryInterface extends \League\OAuth2\Server\Repositories\ClientRepositoryInterface
{
    /**
     * Return information about a client.
     *
     * @param string|int $identifier The client identifier
     *
     * @return \Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface|null
     */
    public function getClientEntityByIdentifier($identifier): ?ClientEntityInterface;
}
