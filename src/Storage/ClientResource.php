<?php

declare(strict_types=1);

namespace sonrac\Auth\Storage;

use League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\ResourceServer;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class ClientResource.
 */
class ClientResource extends ResourceServer implements TokenStorageInterface
{
    protected $accessTokenRepository;

    public function __construct(
        AccessTokenRepositoryInterface $accessTokenRepository,
        $publicKey,
        AuthorizationValidatorInterface $authorizationValidator = null
    ) {
        parent::__construct($accessTokenRepository, $publicKey, $authorizationValidator);

        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
//        $this->get
    }

    /**
     * {@inheritdoc}
     */
    public function setToken(TokenInterface $token = null)
    {
        // TODO: Implement setToken() method.
    }
}
