<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/16/18
 * Time: 12:39 AM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Entity;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

/**
 * Class AccessToken.
 */
class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait, TokenEntityTrait, EntityTrait;

    /**
     * AccessToken constructor.
     *
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     * @param array                                                $scopes
     * @param int|string|null                                      $userIdentifier
     */
    public function __construct(ClientEntityInterface $client, array $scopes = [], $userIdentifier = null)
    {
        $this->client         = $client;
        $this->userIdentifier = $userIdentifier;

        foreach ($scopes as $scope) {
            $this->addScope($scope);
        }
    }
}
