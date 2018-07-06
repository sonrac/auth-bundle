<?php


namespace sonrac\Auth\Security;

/**
 * Interface AuthorizationServerInterface
 */
interface AuthorizationServerInterface
{
    public function authenticate();

    public function authorize();
}