<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/16/18
 * Time: 12:55 AM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Repository;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Sonrac\OAuth2\Bridge\Entity\RefreshToken;
use Sonrac\OAuth2\Entity\RefreshToken as DoctrineRefreshToken;
use Sonrac\OAuth2\Repository\RefreshTokenRepository as DoctrineRefreshTokenRepository;

/**
 * Class RefreshTokenRepository
 * @package Sonrac\OAuth2\Bridge\Repository
 */
class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @var \Sonrac\OAuth2\Repository\RefreshTokenRepository
     */
    private $refreshTokenRepository;

    /**
     * RefreshTokenRepository constructor.
     * @param \Sonrac\OAuth2\Repository\RefreshTokenRepository $refreshTokenRepository
     */
    public function __construct(DoctrineRefreshTokenRepository $refreshTokenRepository)
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
        $refreshToken = new DoctrineRefreshToken();
        $refreshToken->setId($refreshTokenEntity->getIdentifier());
        $refreshToken->setAccessToken($refreshTokenEntity->getAccessToken()->getIdentifier());
        $refreshToken->setExpireAt($refreshTokenEntity->getExpiryDateTime()->getTimestamp());
        $refreshToken->setIsRevoked(false);
        $refreshToken->setCreatedAt(time());

        try {
            $this->refreshTokenRepository->save($refreshToken);
        } catch (UniqueConstraintViolationException $exception) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId)
    {
        $refreshToken = $this->refreshTokenRepository->findOneBy(['id' => $tokenId]);

        if (null === $refreshToken) {
            return;
        }

        $refreshToken->setIsRevoked(true);

        $this->refreshTokenRepository->save($refreshToken);
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        $refreshToken = $this->refreshTokenRepository->findOneBy(['id' => $tokenId]);

        return null === $refreshToken || $refreshToken->isRevoked();
    }
}
