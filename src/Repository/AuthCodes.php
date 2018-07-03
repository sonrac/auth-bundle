<?php

namespace sonrac\Auth\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use sonrac\Auth\Entity\AuthCode;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AuthCodes.
 */
class AuthCodes extends ServiceEntityRepository implements AuthCodeRepositoryInterface
{
    /**
     * AuthCodes constructor.
     *
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AuthCode::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getNewAuthCode()
    {
        // TODO: Implement getNewAuthCode() method.
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        // TODO: Implement persistNewAuthCode() method.
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAuthCode($codeId)
    {
        // TODO: Implement revokeAuthCode() method.
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthCodeRevoked($codeId)
    {
        // TODO: Implement isAuthCodeRevoked() method.
    }
}
