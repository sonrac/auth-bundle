<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/15/18
 * Time: 11:35 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Repository;

use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Sonrac\OAuth2\Bridge\Entity\Client;
use Sonrac\OAuth2\Entity\Client as DoctrineClient;
use Sonrac\OAuth2\Repository\ClientRepository as DoctrineClientRepository;

/**
 * Class ClientRepository
 * @package Sonrac\OAuth2\Bridge\Repository
 */
class ClientRepository implements ClientRepositoryInterface
{
    /**
     * @var \Sonrac\OAuth2\Repository\ClientRepository
     */
    private $clientRepository;

    /**
     * ClientRepository constructor.
     * @param \Sonrac\OAuth2\Repository\ClientRepository $clientRepository
     */
    public function __construct(DoctrineClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true)
    {
        $client = $this->clientRepository->findOneBy(['id' => $clientIdentifier]);

        if (null === $client) {
            throw OAuthServerException::invalidClient();
        }

        if (null !== $grantType && '' !== $grantType && false === $this->hasGrantType($client, $grantType)) {
            throw OAuthServerException::invalidGrant();
        }

        if ($mustValidateSecret && $clientSecret !== $client->getSecret()) {
            throw OAuthServerException::invalidClient();
        }

        return new Client($client->getId(), $client->getName(), $client->getRedirectUris());
    }

    /**
     * @param \Sonrac\OAuth2\Entity\Client $client
     * @param string $grantType
     *
     * @return bool
     */
    protected function hasGrantType(DoctrineClient $client, string $grantType): bool
    {
        return \in_array(\mb_strtolower($grantType), $client->getAllowedGrantTypes(), true);
    }
}
