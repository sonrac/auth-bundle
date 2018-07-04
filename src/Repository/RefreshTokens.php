<?php

namespace sonrac\Auth\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use sonrac\Auth\Entity\RefreshToken;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class RefreshTokens.
 * Refresh token repository.
 *
 * @method RefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefreshToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefreshToken[]    findAll()
 * @method RefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefreshTokens extends ServiceEntityRepository implements RefreshTokenRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RefreshToken::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken()
    {
        $token = new RefreshToken();
        $token->setCreatedAt(\time());

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        /** @var \sonrac\Auth\Entity\RefreshToken $refreshTokenEntity */
        $refreshTokenEntity->preparePersist();
        $this->_em->persist($refreshTokenEntity);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If refresh token not found
     */
    public function revokeRefreshToken($tokenId)
    {
        $token = $this->find($tokenId);

        if (!$token) {
            throw new \InvalidArgumentException('Refresh token not found');
        }

        $token->setIsRevoked(true);
        $token->preparePersist();

        $this->_em->persist($token);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If refresh token not found
     */
    public function isRefreshTokenRevoked($tokenId): bool
    {
        $token = $this->find($tokenId);

        if (!$token) {
            throw new \InvalidArgumentException('Refresh token not found');
        }

        return $token->isRevoked();
    }
}
