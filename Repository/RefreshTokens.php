<?php

namespace sonrac\AuthBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use sonrac\AuthBundle\Entity\RefreshToken;

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
        $this->_em->persist($refreshTokenEntity);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId)
    {
        $token = $this->find($tokenId);

        if (!$token) {
            throw new \InvalidArgumentException('Refresh token not found');
        }

        $token->setIsRevoked(true);

        $this->_em->persist($token);
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
