<?php

declare(strict_types=1);

namespace sonrac\Auth\Entity;

use Openapi\Annotations as OA;
use Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface;

/**
 * Class Client.
 *
 * @OA\Schema(
 *     title="OAuth clients",
 *     description="Oauth clients list",
 *     required={"redirect_uris", "allowed_grant_types", "name"}
 * )
 */
class Client implements ClientEntityInterface
{
    use TimeEntityTrait;

    /**
     * Token response type.
     *
     * @const
     *
     * @var string
     */
    public const RESPONSE_TYPE_TOKEN = 'token';

    /**
     * Authorization code response type.
     *
     * @const
     *
     * @var string
     */
    public const RESPONSE_TYPE_CODE = 'code';

    /**
     * Client secret key.
     *
     * @var string
     *
     * @OA\Property(example="secret", enum={"client_credentials", "password"}, maxLength=2000)
     */
    protected $secret;

    /**
     * Allowed grant types.
     *
     * @var array
     *
     * @OA\Property(
     *     example={"client_credentials", "password"},
     *     @OA\Items(
     *         type="string"
     *     )
     * )
     */
    protected $allowed_grant_types;

    /**
     * Random client identifier.
     *
     * @var string
     *
     * @OA\Property(example="test_application", uniqueItems=true)
     */
    protected $name;

    /**
     * Client app description,.
     *
     * @var string
     *
     * @OA\Property(example="Test application", format="text")
     */
    protected $description;

    /**
     * Redirect url list.
     *
     * @var array
     *
     * @OA\Property(
     *     example={"https://test.com", "https://test.com/redirect"},
     *     @OA\Items(
     *         type="string"
     *     )
     * )
     */
    protected $redirect_uris;

    /**
     * Created time.
     *
     * @var int
     *
     * @OA\Property(format="bigInt", example="1529397813")
     */
    protected $created_at;

    /**
     * Updated time.
     *
     * @var int
     *
     * @OA\Property(format="bigInt", example="1529397813")
     */
    protected $updated_at;

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): string
    {
        return $this->name;
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
    public function setIdentifier(string $identifier): void
    {
        $this->name = $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUri()
    {
        return $this->getRedirectUris();
    }

    /**
     * Get redirect uris.
     *
     * @return array
     */
    public function getRedirectUris(): ?array
    {
        return $this->redirect_uris;
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
    public function getAllowedGrantTypes(): ?array
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
     * Add redirect uri.
     *
     * @param string $uri
     */
    public function addRedirectUri(string $uri): void
    {
        $uri = \mb_strtolower($uri);

        if (false === \in_array($uri, $this->redirect_uris, true)) {
            $this->redirect_uris[] = $uri;
        }
    }

    /**
     * Get client app description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    /**
     * Set client app description.
     *
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
