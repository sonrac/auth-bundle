<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController.
 */
class DefaultController extends AbstractController
{
    public function index(Request $request)
    {
        return $this->json([]);
    }

    public function security(Request $request)
    {
        return $this->json([
            'status' => true,
        ]);
    }
}
