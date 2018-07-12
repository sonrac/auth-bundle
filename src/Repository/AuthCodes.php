<?php

namespace sonrac\Auth\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Psr\Container\ContainerInterface;
use sonrac\Auth\Entity\AuthCode;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AuthCodes.
 */
class AuthCodes extends ServiceEntityRepository implements AuthCodeRepositoryInterface
{
    /**
     * Container interface.
     *
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * AuthCodes constructor.
     *
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry
     * @param \Psr\Container\ContainerInterface          $container
     */
    public function __construct(RegistryInterface $registry, ContainerInterface $container)
    {
        parent::__construct($registry, AuthCode::class);

        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function getNewAuthCode()
    {
        $code = $this->container->get('service_container')->get(AuthCodeEntityInterface::class);

        $code->setCreatedAt(\time());

        return $code;
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $this->_em->persist($authCodeEntity);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If auth code does not found.
     */
    public function revokeAuthCode($codeId)
    {
        $token = $this->find($codeId);
        if (!$token) {
            throw new \InvalidArgumentException('Auth code does not find');
        }

        $token->setIsRevoked(true);
        $this->_em->persist($token);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If auth code does not find
     */
    public function isAuthCodeRevoked($codeId)
    {
        $token = $this->find($codeId);

        if (!$token) {
            throw new \InvalidArgumentException('Auth code token not found');
        }

        return $token->isRevoked();
    }
}
