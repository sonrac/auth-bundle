<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OAuthController.
 */
class OAuthController extends AbstractController
{
    use OAuthAuthorizationHandlerAwareTrait;
    use OAuthIssueTokenHandlerAwareTrait;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function authorizationAction(Request $request): Response
    {
        $response = $this->OAuthAuthorizationHandler->attemptAuthorization($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function issueTokenAction(Request $request): Response
    {
        $response = $this->OAuthIssueTokenHandler->attemptTokenIssue($request);

        return $response;
    }
}
