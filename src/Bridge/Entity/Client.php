<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/15/18
 * Time: 11:49 PM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Entity;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * Class Client.
 */
class Client implements ClientEntityInterface
{
    use ClientTrait, EntityTrait;

    /**
     * Client constructor.
     *
     * @param $identifier
     * @param string          $name
     * @param string|string[] $redirectUri
     */
    public function __construct($identifier, $name, $redirectUri)
    {
        $this->identifier  = $identifier;
        $this->name        = $name;
        $this->redirectUri = $redirectUri;
    }
}
