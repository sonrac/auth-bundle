<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/15/18
 * Time: 11:35 PM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Repository;

use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Sonrac\OAuth2\Adapter\Entity\ClientEntityInterface;
use Sonrac\OAuth2\Adapter\Repository\ClientRepositoryInterface as OAuthClientRepositoryInterface;
use Sonrac\OAuth2\Bridge\Entity\Client;

/**
 * Class ClientRepository.
 */
class ClientRepository implements ClientRepositoryInterface
{
    /**
     * @var \Sonrac\OAuth2\Adapter\Repository\ClientRepositoryInterface
     */
    private $clientRepository;

    /**
     * ClientRepository constructor.
     *
     * @param \Sonrac\OAuth2\Adapter\Repository\ClientRepositoryInterface $clientRepository
     */
    public function __construct(OAuthClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientEntity(
        $clientIdentifier,
        $grantType = null,
        $clientSecret = null,
        $mustValidateSecret = true
    ) {
        $client = $this->clientRepository->findClientEntityByIdentifier($clientIdentifier);

        if (null === $client) {
            throw OAuthServerException::invalidClient();
        }

        if (null !== $grantType && '' !== $grantType && false === $this->hasGrantType($client, $grantType)) {
            throw OAuthServerException::invalidGrant();
        }

        if ($mustValidateSecret && $clientSecret !== $client->getSecret()) {
            throw OAuthServerException::invalidClient();
        }

        return new Client($client->getIdentifier(), $client->getName(), $client->getRedirectUris());
    }

    /**
     * @param \Sonrac\OAuth2\Adapter\Entity\ClientEntityInterface $client
     * @param string                                              $grantType
     *
     * @return bool
     */
    protected function hasGrantType(ClientEntityInterface $client, string $grantType): bool
    {
        return \in_array(\mb_strtolower($grantType), $client->getAllowedGrantTypes(), true);
    }
}
