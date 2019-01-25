<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/5/18
 * Time: 10:30 PM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Config;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\HttpUtils;

/**
 * Class OAuthPathConfig.
 */
class OAuthPathConfig
{
    /**
     * @var \Symfony\Component\Security\Http\HttpUtils
     */
    private $httpUtils;

    /**
     * @var string|null
     */
    private $authorizationPath;

    /**
     * @var string|null
     */
    private $issueTokenPath;

    /**
     * OAuthPathConfig constructor.
     *
     * @param \Symfony\Component\Security\Http\HttpUtils $httpUtils
     * @param string|null                                $authorizationPath
     * @param string|null                                $issueTokenPath
     */
    public function __construct(
        HttpUtils $httpUtils,
        ?string $authorizationPath = null,
        ?string $issueTokenPath = null
    ) {
        $this->httpUtils         = $httpUtils;
        $this->authorizationPath = $authorizationPath;
        $this->issueTokenPath    = $issueTokenPath;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function isAuthorizationPath(Request $request): bool
    {
        return null !== $this->authorizationPath && '' !== $this->authorizationPath
            && $this->httpUtils->checkRequestPath($request, $this->authorizationPath);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function isIssueTokenPath(Request $request): bool
    {
        return null !== $this->issueTokenPath && '' !== $this->issueTokenPath
            && $this->httpUtils->checkRequestPath($request, $this->issueTokenPath);
    }
}
