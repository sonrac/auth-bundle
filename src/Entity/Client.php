<?php

namespace sonrac\Auth\Entity;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use Swagger\Annotations as OAS;

/**
 * Class Client.
 *
 * @OAS\Schema(
 *     title="OAuth clients",
 *     description="Oauth clients list",
 *     required={"redirect_uris", "allowed_grant_types", "name"}
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
    public const GRANT_AUTH_CODE = 'code';

    /**
     * Client identifier.
     *
     * @var int
     *
     * @OAS\Property(example=1)
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
     * User identifier.
     *
     * @var int
     *
     * @OAS\Property(example=1)
     */
    protected $user_id;

    /**
     * Allowed grant types.
     *
     * @var array
     *
     * @OAS\Property(example={"client_credentials", "password"})
     */
    protected $allowed_grant_types;

    /**
     * Random client identifier.
     *
     * @var string
     *
     * @OAS\Property(example="test_application", uniqueItems=true)
     */
    protected $name;

    /**
     * Redirect url list.
     *
     * @var array
     *
     * @OAS\Property(example={"https://test.com", "https://test.com/redirect"})
     */
    protected $redirect_uris;

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
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * Set client name.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Set identifier.
     *
     * @param string|int $identifier
     */
    public function setIdentifier($identifier): void
    {
        $this->id = $identifier;
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
    public function setSecret(string $secret): void
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
        return $this->allowed_grant_types;
    }

    /**
     * Set allowed grant types.
     *
     * @param array $allowed_grant_types
     */
    public function setAllowedGrantTypes(array $allowed_grant_types): void
    {
        $this->allowed_grant_types = $allowed_grant_types;
    }

    /**
     * Get redirect uris.
     *
     * @return array
     */
    public function getRedirectUris(): array
    {
        return $this->redirect_uris ?? [];
    }

    /**
     * Set redirect uris.
     *
     * @param array $redirect_uris
     */
    public function setRedirectUris(array $redirect_uris): void
    {
        $redirect_uris = \array_map(function ($uri) {
            return \mb_strtolower($uri);
        }, $redirect_uris);

        $this->redirect_uris = $redirect_uris;
    }

    /**
     * Add redirect uri.
     *
     * @param string $uri
     */
    public function addRedirectUri(string $uri): void
    {
        $uri = \mb_strtolower($uri);
        if (!\in_array($uri, $this->redirect_uris, true)) {
            $this->redirect_uris[] = $uri;
        }
    }
}
