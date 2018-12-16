<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/2/18
 * Time: 4:38 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Grant;

/**
 * Class AuthCodeGrant
 * @package Sonrac\OAuth2\Bridge\Grant
 */
class AuthCodeGrant extends \League\OAuth2\Server\Grant\AuthCodeGrant
{
    public const TYPE = 'authorization_code';

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return static::TYPE;
    }
}
