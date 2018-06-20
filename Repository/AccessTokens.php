<?php

namespace sonrac\AuthBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use sonrac\AuthBundle\Entity\AccessToken;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AccessTokens.
 *
 * @method AccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessToken[]    findAll()
 * @method AccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessTokens extends ServiceEntityRepository implements AccessTokenRepositoryInterface
{
    use TokenEntityTrait;

    /**
     * AccessTokens constructor.
     *
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $token = new AccessToken();
        foreach ($scopes as $scope) {
            $token->addScope($scope);
        }

        $token->setClient($clientEntity);
        if ($userIdentifier) {
            $this->setUserIdentifier($userIdentifier);
        }

        return $token;
    }

    /**
     * @param \sonrac\AuthBundle\Entity\AccessToken|AccessTokenEntityInterface $accessTokenEntity
     *                                                                                            {@inheritdoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        if (!$accessTokenEntity->getCreatedAt()) {
            $accessTokenEntity->setCreatedAt(\time());
        }

        $this->_em->persist($accessTokenEntity);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAccessToken($tokenId)
    {
        return $this->_em->createQueryBuilder()
            ->update('access_token')
            ->where('id = :id')
            ->set('is_revoked', true)
            ->setParameter('id', $tokenId)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        /** @var \sonrac\AuthBundle\Entity\AccessToken $entity */
        $entity = $this->find($tokenId);

        if (!$entity) {
            throw new \InvalidArgumentException('Token not found');
        }

        return (bool) $entity->isRevoked();
    }
}
