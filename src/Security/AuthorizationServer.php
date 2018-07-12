<?php

declare(strict_types=1);

namespace sonrac\Auth\Security;

use League\OAuth2\Server\AuthorizationServer as LeagueAuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use sonrac\Auth\Entity\Client;
use Zend\Diactoros\Stream;

/**
 * Class AuthorizationServer.
 */
class AuthorizationServer implements AuthorizationServerInterface
{
    /**
     * Enable grant types list.
     *
     * @var array
     */
    private $enableGrantTypes;

    /**
     * Access token ttl.
     *
     * @var array
     */
    private $accessTokenTtl;

    /**
     * Refresh token ttl.
     *
     * @var array
     */
    private $refreshTokenTtl;

    /**
     * Auth code ttl.
     *
     * @var array
     */
    private $authCodeTtl;

    /**
     * Authorization server.
     *
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    private $authorizationServer;

    /**
     * Container.
     *
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * AuthorizationServer constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Exception
     */
    public function __construct(ContainerInterface $container)
    {
        $this->enableGrantTypes = $container->get('service_container')
            ->getParameter('sonrac_auth.enable_grant_types');
        $this->accessTokenTtl = $container->get('service_container')
            ->getParameter('sonrac_auth.access_token_lifetime');
        $this->refreshTokenTtl = $container->get('service_container')
            ->getParameter('sonrac_auth.refresh_token_lifetime');
        $this->authCodeTtl = $container->get('service_container')
            ->getParameter('sonrac_auth.auth_code_lifetime');
        $this->enableGrantTypes = $container->get('service_container')
            ->getParameter('sonrac_auth.enable_grant_types');

        $this->container = $container;

        $this->configureAuthorizationServer();
    }

    /**
     * Configure authorization server.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Exception
     */
    public function configureAuthorizationServer(): void
    {
        $keyPath = $this->container->get('service_container')->getParameter('sonrac_auth.private_key_path')
            .DIRECTORY_SEPARATOR.
            $this->container->get('service_container')->getParameter('sonrac_auth.private_key_name');

        $privateKey = $this->container->get('service_container')->getParameter('sonrac_auth.pass_phrase') ?
            new CryptKey(
                $keyPath,
                $this->container->get('service_container')->getParameter('sonrac_auth.pass_phrase')
            ) : $keyPath;
        $encryptionKey = $this->container->get('service_container')->getParameter('sonrac_auth.encryption_key');

        $this->authorizationServer = new LeagueAuthorizationServer(
            $this->container->get(ClientRepositoryInterface::class),
            $this->container->get(AccessTokenRepositoryInterface::class),
            $this->container->get(ScopeRepositoryInterface::class),
            $privateKey,
            $encryptionKey
        );

        $this->enableGrantTypes();
    }

    /**
     * Enable grant types for service/.
     *
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    protected function enableGrantTypes(): void
    {
        foreach ($this->enableGrantTypes as $grantType => $isEnabled) {
            if ($isEnabled) {
                $grantTypeObject = null;
                $ttl             = $this->getTokenTtl($this->accessTokenTtl);
                switch ($grantType) {
                    case Client::GRANT_CLIENT_CREDENTIALS:
                        /** @var AuthorizationServer $a */
                        $grantTypeObject = new ClientCredentialsGrant();
                        break;
                    case Client::GRANT_PASSWORD:
                        $grantTypeObject = new PasswordGrant(
                            $this->container->get(UserRepositoryInterface::class),
                            $this->container->get(RefreshTokenRepositoryInterface::class)
                        );
                        break;
                    case Client::GRANT_AUTH_CODE:
                        $grantTypeObject = new AuthCodeGrant(
                            $this->container->get(AuthCodeRepositoryInterface::class),
                            $this->container->get(RefreshTokenRepositoryInterface::class),
                            $this->getTokenTtl($this->authCodeTtl)
                        );
                        $grantTypeObject->setRefreshTokenTTL($this->getTokenTtl($this->refreshTokenTtl));
                        break;
                    case Client::GRANT_IMPLICIT:
                        $grantTypeObject = new ImplicitGrant(
                            $ttl,
                            $this->container->get('service_container')->getParameter('sonrac_auth.query_delimiter')
                        );
                        break;
                    case Client::GRANT_REFRESH_TOKEN:
                        $grantTypeObject = new RefreshTokenGrant(
                            $this->container->get(RefreshTokenRepositoryInterface::class)
                        );
                        break;
                }

                if ($grantTypeObject) {
                    $this->authorizationServer->enableGrantType($grantTypeObject, $ttl);
                }
            }
        }
    }

    /**
     * Get token ttl.
     *
     * @param int $ttl
     *
     * @throws \Exception
     *
     * @return null|\DateInterval
     */
    private function getTokenTtl($ttl): ?\DateInterval
    {
        return $ttl ? new \DateInterval('PT'.$ttl.'S') : null;
    }

    /**
     * Authorize action.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     *
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function token(ServerRequestInterface $request, ResponseInterface $response)
    {
        $request = $this->addScopesToRequest($request);

        try {
            return $this->getAuthorizationServer()->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            $body = new Stream('php://temp', 'r+');
            $body->write(\json_encode([
                'message'    => 'Internal server error',
                'error_test' => $exception->getMessage(),
                'error'      => 'internal_error',
            ]));

            return $response->withBody($body)
                ->withAddedHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    /**
     * Get authorization server.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return \League\OAuth2\Server\AuthorizationServer
     */
    public function getAuthorizationServer(): LeagueAuthorizationServer
    {
        return $this->authorizationServer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function authorize(ServerRequestInterface $request, ResponseInterface $response)
    {
        $request = $this->addScopesToRequest($request);

        try {
            $authRequest = $this->getAuthorizationServer()->validateAuthorizationRequest($request);
            $authRequest->setUser($this->container->get('service_container')->get(UserEntityInterface::class));
            $authRequest->setAuthorizationApproved(true);

            return $this->getAuthorizationServer()->completeAuthorizationRequest($authRequest, $response);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        }
    }

    /**
     * Add scopes in request if needed.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    protected function addScopesToRequest(ServerRequestInterface $request): ServerRequestInterface
    {
        $scopes = $this->container->get('service_container')->getParameter('sonrac_auth.default_scopes');
        if ($scopes && !$request->getAttribute('scopes')) {
            $request->withAttribute('scopes', $scopes);
        }

        return $request;
    }
}
