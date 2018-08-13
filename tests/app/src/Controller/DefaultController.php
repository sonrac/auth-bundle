<?php

namespace sonrac\Auth\Tests\App\Controller;

use sonrac\Auth\Repository\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController.
 */
class DefaultController extends AbstractController
{
    public function index(Request $request)
    {
        \var_dump($this->get('service_container')->get('sonrac_auth.resource_server'));

        return $this->json([]);
    }

    public function security(Request $request) {

        return $this->json([
            'status' => true
        ]);
    }
}
