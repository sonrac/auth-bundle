<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/18/18
 * Time: 10:42 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Adapter\Repository;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

/**
 * Interface AccessTokenRepositoryInterface
 * @package Sonrac\OAuth2\Adapter\Repository
 */
interface AccessTokenRepositoryInterface
{
    /**
     * @param \League\OAuth2\Server\Entities\AccessTokenEntityInterface $accessTokenEntity
     *
     * @return void
     *
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     *
     * @see \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface::persistNewAccessToken()
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void;

    /**
     * @param string|int $tokenId
     *
     * @return void
     *
     * @see \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface::revokeAccessToken()
     */
    public function revokeAccessToken($tokenId): void;

    /**
     * @param string|int $tokenId
     *
     * @return bool
     *
     * @see \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface::isAccessTokenRevoked()
     */
    public function isAccessTokenRevoked($tokenId): bool;
}
