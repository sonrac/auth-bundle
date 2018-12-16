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
 * Class ImplicitGrant
 * @package Sonrac\OAuth2\Bridge\Grant
 */
class ImplicitGrant extends \League\OAuth2\Server\Grant\ImplicitGrant
{
    public const TYPE = 'implicit';

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return static::TYPE;
    }
}
