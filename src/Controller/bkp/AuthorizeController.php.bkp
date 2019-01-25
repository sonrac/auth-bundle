<?php

declare(strict_types=1);

namespace sonrac\Auth\Controller;

use Psr\Http\Message\ServerRequestInterface;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Zend\Diactoros\Response;

/**
 * Class AuthorizeController.
 *
 * @OA\Info(
 *     title="OAuth2 example API",
 *     description="OAuth2 example API",
 *     version="1.0"
 * )
 *
 * @OA\Server(
 *     description="Auth server",
 *     url="SWAGGER_URL"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="oauth2",
 *     name="oauth2",
 *     type="oauth2",
 *     description="OAuth2",
 *     @OA\Flow(
 *         flow="implicit",
 *         authorizationUrl="/api/authorize",
 *         scopes={
 *             "default" : "Default scope",
 *             "client" : "User access",
 *             "admin" : "Admin access"
 *         }
 *     ),
 *     @OA\Flow(
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
 *     @OA\Flow(
 *         flow="password",
 *         tokenUrl="/api/authorize",
 *         refreshUrl="/api/authorize",
 *         scopes={
 *             "default" : "Default scope",
 *             "client" : "User access",
 *             "admin" : "Admin access"
 *         }
 *     ),
 *     @OA\Flow(
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

        return $response;
//        return $this->get('sonrac_auth.authorization_server')->token($request, $response);
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

        return $response;
//        return $this->get('sonrac_auth.authorization_server')->authorize($request, $response);
    }
}
