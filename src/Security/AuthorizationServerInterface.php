<?php

declare(strict_types=1);

namespace sonrac\Auth\Security;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface AuthorizationServerInterface
 */
interface AuthorizationServerInterface
{
    /**
     * Authorize.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     *
     * @return mixed
     */
    public function token(ServerRequestInterface $request, ResponseInterface $response);

    /**
     * Authenticate third party.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     *
     * @return mixed
     */
    public function authorize(ServerRequestInterface $request, ResponseInterface $response);
}