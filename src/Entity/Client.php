<?php

namespace sonrac\Auth\Entity;

use Doctrine\DBAL\Connection;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Psr\Container\ContainerInterface;
use Swagger\Annotations as OAS;

/**
 * Class Client.
 *
 * @OAS\Schema(
 *     title="OAuth clients",
 *     description="Oauth clients list",
 *     required={"redirectUris", "allowedGrantTypes", "client_name"}
 * )
 */
class Client implements ClientEntityInterface
{
    use TimeEntityTrait;

    /**
     * Client credentials grant type.
     *
     * @const
     *
     * @var string
     */
    public const GRANT_CLIENT_CREDENTIALS = 'clientCredentials';

    /**
     * Client credentials grant type.
     *
     * @const
     *
     * @var string
     */
    public const GRANT_PASSWORD = 'password';

    /**
     * Implicit grant type.
     *
     * @const
     *
     * @var string
     */
    public const GRANT_IMPLICIT = 'implicit';

    /**
     * Auth code grant type.
     *
     * @const
     *
     * @var string
     */
    public const GRANT_AUTH_CODE = 'auth_code';

    /**
     * Client identifier.
     *
     * @var int
     *
     * @OAS\Property(example=1, uniqueItems=true)
     */
    protected $id;

    /**
     * Client secret key.
     *
     * @var string
     *
     * @OAS\Property(example="secret", enum={"client_credentials", "password"}, maxLength=2000)
     */
    protected $secret;

    /**
     * Allowed grant types.
     *
     * @var array
     *
     * @OAS\Property(example={"client_credentials", "password"})
     */
    protected $allowedGrantTypes;

    /**
     * Random client identifier.
     *
     * @var string
     *
     * @OAS\Property(example="test_application", uniqueItems=true)
     */
    protected $client_name;

    /**
     * Redirect url list.
     *
     * @var array
     *
     * @OAS\Property(example={"https://test.com", "https://test.com/redirect"})
     */
    protected $redirectUris;

    /**
     * Connection.
     *
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * Created time.
     *
     * @var int
     *
     * @OAS\Property(format="bigInt", example="1529397813")
     */
    protected $created_at;

    /**
     * Updated time.
     *
     * @var int
     *
     * @OAS\Property(format="bigInt", example="1529397813")
     */
    protected $updated_at;

    /**
     * Container registry.
     *
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * MailTemplates constructor.
     *
     * @param \Doctrine\DBAL\Connection         $connection
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(Connection $connection, ContainerInterface $container)
    {
        $this->connection = $connection;
        $this->container  = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * Set identifier.
     *
     * @param string|int $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->id = $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->client_name;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUri()
    {
        return $this->getRedirectUris();
    }

    /**
     * Get secret key.
     *
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * Set secret key.
     *
     * @param string $secret
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * Get allowed grant types.
     *
     * @return array
     */
    public function getAllowedGrantTypes(): array
    {
        return $this->allowedGrantTypes;
    }

    /**
     * Set allowed grant types.
     *
     * @param array $allowedGrantTypes
     */
    public function setAllowedGrantTypes(array $allowedGrantTypes)
    {
        $this->allowedGrantTypes = $allowedGrantTypes;
    }

    /**
     * Get client name.
     *
     * @return string
     */
    public function getClientName(): string
    {
        return $this->client_name;
    }

    /**
     * @param string $client_name
     */
    public function setClientName(string $client_name)
    {
        $this->client_name = $client_name;
    }

    /**
     * Get redirect uris.
     *
     * @return array
     */
    public function getRedirectUris(): array
    {
        return $this->redirectUris ?? [];
    }

    /**
     * Set redirect uris.
     *
     * @param array $redirectUris
     */
    public function setRedirectUris(array $redirectUris)
    {
        $redirectUris = \array_map(function ($uri) {
            return \mb_strtolower($uri);
        }, $redirectUris);

        $this->redirectUris = $redirectUris;
    }

    /**
     * Add redirect uri.
     *
     * @param string $uri
     */
    public function addRedirectUri(string $uri)
    {
        $uri = \mb_strtolower($uri);
        if (!\in_array($uri, $this->redirectUris, true)) {
            $this->redirectUris[] = $uri;
        }
    }
}
