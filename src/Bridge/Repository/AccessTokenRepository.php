<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/16/18
 * Time: 12:43 AM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Repository;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Sonrac\OAuth2\Adapter\Repository\AccessTokenRepositoryInterface as OAuthAccessTokenRepositoryInterface;
use Sonrac\OAuth2\Bridge\Entity\AccessToken;

/**
 * Class AccessTokenRepository
 * @package Sonrac\OAuth2\Bridge\Repository
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * @var \Sonrac\OAuth2\Adapter\Repository\AccessTokenRepositoryInterface
     */
    private $accessTokenRepository;

    /**
     * AccessTokenRepository constructor.
     * @param \Sonrac\OAuth2\Adapter\Repository\AccessTokenRepositoryInterface $accessTokenRepository
     */
    public function __construct(OAuthAccessTokenRepositoryInterface $accessTokenRepository)
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
        $this->accessTokenRepository->persistNewAccessToken($accessTokenEntity);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAccessToken($tokenId)
    {
        $this->accessTokenRepository->revokeAccessToken($tokenId);
    }

    /**
     * {@inheritdoc}
     */
    public function isAccessTokenRevoked($tokenId)
    {
        return $this->accessTokenRepository->isAccessTokenRevoked($tokenId);
    }
}
