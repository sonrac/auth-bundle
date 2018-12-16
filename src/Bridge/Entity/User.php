<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/16/18
 * Time: 1:04 AM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Entity;

use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * Class User
 * @package Sonrac\OAuth2\Bridge\Entity
 */
class User implements UserEntityInterface
{
    use EntityTrait;

    /**
     * User constructor.
     * @param $userIdentifier
     */
    public function __construct($userIdentifier)
    {
        $this->identifier = $userIdentifier;
    }
}
