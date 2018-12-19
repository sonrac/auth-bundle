<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/19/18
 * Time: 9:22 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Adapter\Entity;

/**
 * Interface ClientEntityInterface
 * @package Sonrac\OAuth2\Adapter\Entity
 */
interface ClientEntityInterface
{
    /**
     * @return string|int
     */
    public function getIdentifier();

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getSecret(): string;

    /**
     * @return string[]
     */
    public function getRedirectUris(): array;

    /**
     * @return string[]
     */
    public function getAllowedGrantTypes(): array;
}
