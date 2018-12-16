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
 * Class RefreshTokenGrant
 * @package Sonrac\OAuth2\Bridge\Grant
 */
class RefreshTokenGrant extends \League\OAuth2\Server\Grant\RefreshTokenGrant
{
    public const TYPE = 'refresh_token';

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return static::TYPE;
    }
}
