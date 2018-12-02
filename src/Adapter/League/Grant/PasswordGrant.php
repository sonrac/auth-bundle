<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/2/18
 * Time: 4:38 PM
 */

declare(strict_types=1);


namespace Sonrac\OAuth2\Adapter\League\Grant;

/**
 * Class PasswordGrant
 * @package Sonrac\OAuth2\Adapter\League\Grant
 */
class PasswordGrant extends \League\OAuth2\Server\Grant\PasswordGrant
{
    public const TYPE = 'password';

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return static::TYPE;
    }
}
