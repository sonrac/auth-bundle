<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use Sonrac\OAuth2\Adapter\Repository\AuthCodeRepositoryInterface;
use Sonrac\OAuth2\Entity\AuthCode;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AuthCodeRepository
 * @package Sonrac\OAuth2\Repository
 *
 * @method AuthCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthCode[]    findAll()
 * @method AuthCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthCodeRepository extends ServiceEntityRepository implements AuthCodeRepositoryInterface
{
    /**
     * AuthCodeRepository constructor.
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AuthCode::class);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        $authCode = new AuthCode();
        $authCode->setId($authCodeEntity->getIdentifier());
        $authCode->setClientId($authCodeEntity->getClient()->getIdentifier());
        $authCode->setUserId(
            null !== $authCodeEntity->getUserIdentifier() ? (int)$authCodeEntity->getUserIdentifier() : null
        );
        $authCode->setRedirectUri($authCodeEntity->getRedirectUri());
        $authCode->setScopes(array_map(function (ScopeEntityInterface $scope) {
            return $scope->getIdentifier();
        }, $authCodeEntity->getScopes()));
        $authCode->setExpireAt($authCodeEntity->getExpiryDateTime()->getTimestamp());
        $authCode->setIsRevoked(false);
        $authCode->setCreatedAt(time());

        try {
            $this->_em->persist($authCode);
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
    public function revokeAuthCode($codeId): void
    {
        $authCode = $this->findOneBy(['id' => $codeId]);

        if (null === $authCode) {
            return;
        }

        $authCode->setIsRevoked(true);

        $this->_em->persist($authCode);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthCodeRevoked($codeId): bool
    {
        $authCode = $this->findOneBy(['id' => $codeId]);

        return null === $authCode || $authCode->isRevoked();
    }
}
