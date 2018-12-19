<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/19/18
 * Time: 9:39 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Adapter\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface UserEntityInterface
 * @package Sonrac\OAuth2\Adapter\Entity
 */
interface UserEntityInterface extends UserInterface
{
    /**
     * @return string|int
     */
    public function getIdentifier();
}
