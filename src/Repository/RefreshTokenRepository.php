<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use Sonrac\OAuth2\Adapter\Repository\RefreshTokenRepositoryInterface;
use Sonrac\OAuth2\Entity\RefreshToken;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class RefreshTokenRepository
 * @package Sonrac\OAuth2\Repository
 *
 * @method RefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefreshToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefreshToken[]    findAll()
 * @method RefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefreshTokenRepository extends ServiceEntityRepository implements RefreshTokenRepositoryInterface
{
    /**
     * RefreshTokenRepository constructor.
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RefreshToken::class);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        $refreshToken = new RefreshToken();
        $refreshToken->setId($refreshTokenEntity->getIdentifier());
        $refreshToken->setAccessToken($refreshTokenEntity->getAccessToken()->getIdentifier());
        $refreshToken->setExpireAt($refreshTokenEntity->getExpiryDateTime()->getTimestamp());
        $refreshToken->setIsRevoked(false);
        $refreshToken->setCreatedAt(time());

        try {
            $this->_em->persist($refreshToken);
            $this->_em->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function revokeRefreshToken($tokenId): void
    {
        $refreshToken = $this->findOneBy(['id' => $tokenId]);

        if (null === $refreshToken) {
            return;
        }

        $refreshToken->setIsRevoked(true);

        $this->_em->persist($refreshToken);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId): bool
    {
        $refreshToken = $this->findOneBy(['id' => $tokenId]);

        return null === $refreshToken || $refreshToken->isRevoked();
    }
}
