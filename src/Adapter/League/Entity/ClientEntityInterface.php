<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/2/18
 * Time: 3:41 PM
 */

declare(strict_types=1);


namespace Sonrac\OAuth2\Adapter\League\Entity;

/**
 * Interface ClientEntityInterface
 * @package Sonrac\OAuth2\Adapter\League\Entity
 */
interface ClientEntityInterface extends \League\OAuth2\Server\Entities\ClientEntityInterface
{
    /**
     * @return string
     */
    public function getSecret(): string;
}
