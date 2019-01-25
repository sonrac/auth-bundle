<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/16/18
 * Time: 12:55 AM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Repository;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Sonrac\OAuth2\Adapter\Repository\RefreshTokenRepositoryInterface as OAuthRefreshTokenRepositoryInterface;
use Sonrac\OAuth2\Bridge\Entity\RefreshToken;

/**
 * Class RefreshTokenRepository
 * @package Sonrac\OAuth2\Bridge\Repository
 */
class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @var \Sonrac\OAuth2\Adapter\Repository\RefreshTokenRepositoryInterface
     */
    private $refreshTokenRepository;

    /**
     * RefreshTokenRepository constructor.
     * @param \Sonrac\OAuth2\Adapter\Repository\RefreshTokenRepositoryInterface $refreshTokenRepository
     */
    public function __construct(OAuthRefreshTokenRepositoryInterface $refreshTokenRepository)
    {
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken()
    {
        return new RefreshToken();
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $this->refreshTokenRepository->persistNewRefreshToken($refreshTokenEntity);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId)
    {
        $this->refreshTokenRepository->revokeRefreshToken($tokenId);
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        return $this->refreshTokenRepository->isRefreshTokenRevoked($tokenId);
    }
}
