<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/16/18
 * Time: 12:19 AM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Repository;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Sonrac\OAuth2\Bridge\Entity\AuthCode;
use Sonrac\OAuth2\Entity\AuthCode as DoctrineAuthCode;
use Sonrac\OAuth2\Repository\AuthCodeRepository as DoctrineAuthCodeRepository;

/**
 * Class AuthCodeRepository
 * @package Sonrac\OAuth2\Bridge\Repository
 */
class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    /**
     * @var \Sonrac\OAuth2\Repository\AuthCodeRepository
     */
    private $authCodeRepository;

    /**
     * AuthCodeRepository constructor.
     * @param \Sonrac\OAuth2\Repository\AuthCodeRepository $authCodeRepository
     */
    public function __construct(DoctrineAuthCodeRepository $authCodeRepository)
    {
        $this->authCodeRepository = $authCodeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewAuthCode()
    {
        return new AuthCode();
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $authCode = new DoctrineAuthCode();
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
            $this->authCodeRepository->save($authCode);
        } catch (UniqueConstraintViolationException $exception) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAuthCode($codeId)
    {
        $authCode = $this->authCodeRepository->findOneBy(['id' => $codeId]);

        if (null === $authCode) {
            return;
        }

        $authCode->setIsRevoked(true);

        $this->authCodeRepository->save($authCode);
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthCodeRevoked($codeId)
    {
        $authCode = $this->authCodeRepository->findOneBy(['id' => $codeId]);

        return null === $authCode || $authCode->isRevoked();
    }
}
