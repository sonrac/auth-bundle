<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/16/18
 * Time: 12:08 AM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Entity;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

/**
 * Class AuthCode.
 */
class AuthCode implements AuthCodeEntityInterface
{
    use AuthCodeTrait, TokenEntityTrait, EntityTrait;
}
