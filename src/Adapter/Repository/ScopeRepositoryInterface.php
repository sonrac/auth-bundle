<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/19/18
 * Time: 9:32 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Adapter\Repository;

use Sonrac\OAuth2\Adapter\Entity\ScopeEntityInterface;

/**
 * Interface ScopeRepositoryInterface
 * @package Sonrac\OAuth2\Adapter\Repository
 */
interface ScopeRepositoryInterface
{
    /**
     * @param string $identifier
     *
     * @return \Sonrac\OAuth2\Adapter\Entity\ScopeEntityInterface|null
     */
    public function findScopeEntityByIdentifier(string $identifier): ?ScopeEntityInterface;
}
