<?php

namespace sonrac\Auth\Tests\App\Controller;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController.
 */
class DefaultController extends AbstractController
{
    public function index(Request $request)
    {
        \var_dump($this->get('service_container')->get(AccessTokenRepositoryInterface::class));

        return $this->json([]);
    }
}
