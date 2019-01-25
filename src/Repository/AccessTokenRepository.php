<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use Sonrac\OAuth2\Adapter\Repository\AccessTokenRepositoryInterface;
use Sonrac\OAuth2\Entity\AccessToken;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AccessTokenRepository.
 *
 * @method AccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessToken[]    findAll()
 * @method AccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessTokenRepository extends ServiceEntityRepository implements AccessTokenRepositoryInterface
{
    /**
     * AccessTokenRepository constructor.
     *
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        $accessToken = new AccessToken();
        $accessToken->setId($accessTokenEntity->getIdentifier());
        $accessToken->setClientId($accessTokenEntity->getClient()->getIdentifier());
        $accessToken->setUserId(
            null !== $accessTokenEntity->getUserIdentifier() ? (int) $accessTokenEntity->getUserIdentifier() : null
        );
        $accessToken->setScopes(\array_map(function (ScopeEntityInterface $scope) {
            return $scope->getIdentifier();
        }, $accessTokenEntity->getScopes()));
        $accessToken->setExpireAt($accessTokenEntity->getExpiryDateTime()->getTimestamp());
        $accessToken->setIsRevoked(false);
        $accessToken->setCreatedAt(\time());

        try {
            $this->_em->persist($accessToken);
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
    public function revokeAccessToken($tokenId): void
    {
        $accessToken = $this->findOneBy(['id' => $tokenId]);

        if (null === $accessToken) {
            return;
        }

        $accessToken->setIsRevoked(true);

        $this->_em->persist($accessToken);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        $accessToken = $this->findOneBy(['id' => $tokenId]);

        return null === $accessToken || $accessToken->isRevoked();
    }
}
