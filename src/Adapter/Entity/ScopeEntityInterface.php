<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/19/18
 * Time: 9:32 PM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Adapter\Entity;

/**
 * Interface ScopeEntityInterface.
 */
interface ScopeEntityInterface
{
    /**
     * @return string
     */
    public function getIdentifier(): string;
}
