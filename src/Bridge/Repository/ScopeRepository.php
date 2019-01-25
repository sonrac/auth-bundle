<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/15/18
 * Time: 11:31 PM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Sonrac\OAuth2\Adapter\Repository\ScopeRepositoryInterface as OAuthScopeRepositoryInterface;
use Sonrac\OAuth2\Bridge\Entity\Scope;

/**
 * Class ScopeRepository.
 */
class ScopeRepository implements ScopeRepositoryInterface
{
    /**
     * @var \Sonrac\OAuth2\Adapter\Repository\ScopeRepositoryInterface
     */
    private $scopeRepository;

    /**
     * ScopeRepository constructor.
     *
     * @param \Sonrac\OAuth2\Adapter\Repository\ScopeRepositoryInterface $scopeRepository
     */
    public function __construct(OAuthScopeRepositoryInterface $scopeRepository)
    {
        $this->scopeRepository = $scopeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        $scope = $this->scopeRepository->findScopeEntityByIdentifier($identifier);

        if (null === $scope) {
            throw OAuthServerException::invalidScope($identifier);
        }

        return new Scope($scope->getIdentifier());
    }

    /**
     * {@inheritdoc}
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        return $scopes;
    }
}
