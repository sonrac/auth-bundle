<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/18/18
 * Time: 10:57 PM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Adapter\Repository;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

/**
 * Interface RefreshTokenRepositoryInterface.
 */
interface RefreshTokenRepositoryInterface
{
    /**
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     *
     * @see \League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface::persistNewRefreshToken()
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void;

    /**
     * @param string|int $tokenId
     *
     * @see \League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface::revokeRefreshToken()
     */
    public function revokeRefreshToken($tokenId): void;

    /**
     * @param string|int $tokenId
     *
     * @return bool
     *
     * @see \League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface::isRefreshTokenRevoked()
     */
    public function isRefreshTokenRevoked($tokenId): bool;
}
