<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Entity;

use Sonrac\OAuth2\Adapter\Entity\ClientEntityInterface;

/**
 * Class Client.
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
     * Client identifier.
     *
     * @var string|null
     */
    private $id;

    /**
     * Client name.
     *
     * @var string|null
     */
    protected $name;

    /**
     * Client app description,.
     *
     * @var string|null
     */
    protected $description;

    /**
     * Client secret key.
     *
     * @var string|null
     */
    protected $secret;

    /**
     * Allowed grant types.
     *
     * @var array|null
     */
    protected $allowedGrantTypes;

    /**
     * Redirect url list.
     *
     * @var array|null
     */
    protected $redirectUris;

    /**
     * Created time.
     *
     * @var int
     */
    protected $createdAt;

    /**
     * Updated time.
     *
     * @var int
     */
    protected $updatedAt;

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * Get client id.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set client id.
     *
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
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
     * Get client app description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
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

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getAllowedGrantTypes(): array
    {
        return null !== $this->allowedGrantTypes ? $this->allowedGrantTypes : [];
    }

    /**
     * Set allowed grant types.
     *
     * @param array $allowedGrantTypes
     */
    public function setAllowedGrantTypes(array $allowedGrantTypes): void
    {
        $this->allowedGrantTypes = $allowedGrantTypes;
    }

    /**
     * Add allowed grant type.
     *
     * @param string $grantType
     */
    public function addAllowedGrantType(string $grantType): void
    {
        $grantType = \mb_strtolower($grantType);

        if (false === \in_array($grantType, $this->allowedGrantTypes, true)) {
            $this->allowedGrantTypes[] = $grantType;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUris(): array
    {
        return null !== $this->redirectUris ? $this->redirectUris : [];
    }

    /**
     * Set redirect uris.
     *
     * @param array $redirectUris
     */
    public function setRedirectUris(array $redirectUris): void
    {
        $this->redirectUris = \array_map(function ($uri) {
            return \mb_strtolower($uri);
        }, $redirectUris);
    }

    /**
     * Add redirect uri.
     *
     * @param string $uri
     */
    public function addRedirectUri(string $uri): void
    {
        $uri = \mb_strtolower($uri);

        if (false === \in_array($uri, $this->redirectUris, true)) {
            $this->redirectUris[] = $uri;
        }
    }
}
