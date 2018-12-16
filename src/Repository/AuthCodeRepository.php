<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
class AuthCodeRepository extends ServiceEntityRepository
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
     * @param \Sonrac\OAuth2\Entity\AuthCode $authCode
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(AuthCode $authCode): void
    {
        $this->_em->persist($authCode);
        $this->_em->flush();
    }
}
