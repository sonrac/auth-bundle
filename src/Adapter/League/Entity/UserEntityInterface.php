<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/2/18
 * Time: 4:49 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Adapter\League\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface UserEntityInterface
 * @package Sonrac\OAuth2\Adapter\League\Entity
 */
interface UserEntityInterface extends \League\OAuth2\Server\Entities\UserEntityInterface, UserInterface
{

}
