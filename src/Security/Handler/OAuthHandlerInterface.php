<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/4/18
 * Time: 11:09 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface OAuthHandlerInterface
 * @package Sonrac\OAuth2\Security\Handler
 */
interface OAuthHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function requires(Request $request): bool;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function handle(Request $request): ?Response;
}
