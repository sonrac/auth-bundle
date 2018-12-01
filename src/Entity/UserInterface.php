<?php

declare(strict_types=1);

namespace sonrac\Auth\Entity;

use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * Interface UserInterface
 * @package sonrac\Auth\Entity
 */
interface UserInterface extends \Symfony\Component\Security\Core\User\UserInterface, UserEntityInterface
{

}
