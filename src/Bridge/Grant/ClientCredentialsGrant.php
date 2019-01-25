<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/2/18
 * Time: 4:38 PM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Grant;

/**
 * Class ClientCredentialsGrant.
 */
class ClientCredentialsGrant extends \League\OAuth2\Server\Grant\ClientCredentialsGrant
{
    public const TYPE = 'client_credentials';

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return static::TYPE;
    }
}
