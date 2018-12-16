<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/16/18
 * Time: 12:43 AM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Repository;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Sonrac\OAuth2\Bridge\Entity\AccessToken;
use Sonrac\OAuth2\Entity\AccessToken as DoctrineAccessToken;
use Sonrac\OAuth2\Repository\AccessTokenRepository as DoctrineAccessTokenRepository;

/**
 * Class AccessTokenRepository
 * @package Sonrac\OAuth2\Bridge\Repository
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * @var \Sonrac\OAuth2\Repository\AccessTokenRepository
     */
    private $accessTokenRepository;

    /**
     * AccessTokenRepository constructor.
     * @param \Sonrac\OAuth2\Repository\AccessTokenRepository $accessTokenRepository
     */
    public function __construct(DoctrineAccessTokenRepository $accessTokenRepository)
    {
        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        return new AccessToken($clientEntity, $scopes, $userIdentifier);
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $accessToken = new DoctrineAccessToken();
        $accessToken->setId($accessTokenEntity->getIdentifier());
        $accessToken->setClientId($accessTokenEntity->getClient()->getIdentifier());
        $accessToken->setUserId(
            null !== $accessTokenEntity->getUserIdentifier() ? (int)$accessTokenEntity->getUserIdentifier() : null
        );
        $accessToken->setScopes(array_map(function (ScopeEntityInterface $scope) {
            return $scope->getIdentifier();
        }, $accessTokenEntity->getScopes()));
        $accessToken->setExpireAt($accessTokenEntity->getExpiryDateTime()->getTimestamp());
        $accessToken->setIsRevoked(false);
        $accessToken->setCreatedAt(time());

        try {
            $this->accessTokenRepository->save($accessToken);
        } catch (UniqueConstraintViolationException $exception) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAccessToken($tokenId)
    {
        $accessToken = $this->accessTokenRepository->findOneBy(['id' => $tokenId]);

        if (null === $accessToken) {
            return;
        }

        $accessToken->setIsRevoked(true);

        $this->accessTokenRepository->save($accessToken);
    }

    /**
     * {@inheritdoc}
     */
    public function isAccessTokenRevoked($tokenId)
    {
        $accessToken = $this->accessTokenRepository->findOneBy(['id' => $tokenId]);

        return null === $accessToken || $accessToken->isRevoked();
    }
}
