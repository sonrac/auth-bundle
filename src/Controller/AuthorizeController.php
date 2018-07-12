<?php

declare(strict_types=1);

namespace sonrac\Auth\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Swagger\Annotations as OAS;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Zend\Diactoros\Response;

/**
 * Class AuthorizeController.
 *
 * @OAS\Info(
 *     title="OAuth2 example API",
 *     description="OAuth2 example API",
 *     version="1.0"
 * )
 *
 * @OAS\Server(
 *     description="Auth server",
 *     url="SWAGGER_URL"
 * )
 *
 * @OAS\SecurityScheme(
 *     securityScheme="oauth2",
 *     name="oauth2",
 *     description="OAuth2",
 *     @OAS\Flow(
 *         flow="implicit",
 *         authorizationUrl="/api/authorize",
 *         scopes={
 *             "default" : "Default scope",
 *             "client" : "User access",
 *             "admin" : "Admin access"
 *         }
 *     ),
 *     @OAS\Flow(
 *         flow="authorizationCode",
 *         authorizationUrl="/api/authorize",
 *         tokenUrl="/api/authorize",
 *         refreshUrl="/api/authorize",
 *         scopes={
 *             "default" : "Default scope",
 *             "client" : "User access",
 *             "admin" : "Admin access"
 *         }
 *     ),
 *     @OAS\Flow(
 *         flow="password",
 *         tokenUrl="/api/authorize",
 *         refreshUrl="/api/authorize",
 *         scopes={
 *             "default" : "Default scope",
 *             "client" : "User access",
 *             "admin" : "Admin access"
 *         }
 *     ),
 *     @OAS\Flow(
 *         flow="clientCredentials",
 *         tokenUrl="/api/authorize",
 *         refreshUrl="/api/authorize",
 *         scopes={
 *             "default" : "Default scope",
 *             "client" : "User access",
 *             "admin" : "Admin access"
 *         }
 *     ),
 * )
 */
class AuthorizeController extends AbstractController
{
    /**
     * Authorize in api action.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     *
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function token(ServerRequestInterface $request)
    {
        $response = new Response();

        return $this->get('sonrac_auth.authorization_server')->token($request, $response);
    }

    /**
     * Third party authorize.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     *
     * @return \Psr\Http\Message\ResponseInterface|static
     */
    public function authorize(ServerRequestInterface $request)
    {
        $response = new Response();

        return $this->get('sonrac_auth.authorization_server')->authorize($request, $response);
    }
}
