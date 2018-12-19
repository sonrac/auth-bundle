<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/16/18
 * Time: 12:19 AM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Repository;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Sonrac\OAuth2\Adapter\Repository\AuthCodeRepositoryInterface as OAuthAuthCodeRepositoryInterface;
use Sonrac\OAuth2\Bridge\Entity\AuthCode;

/**
 * Class AuthCodeRepository
 * @package Sonrac\OAuth2\Bridge\Repository
 */
class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    /**
     * @var \Sonrac\OAuth2\Adapter\Repository\AuthCodeRepositoryInterface
     */
    private $authCodeRepository;

    /**
     * AuthCodeRepository constructor.
     * @param \Sonrac\OAuth2\Adapter\Repository\AuthCodeRepositoryInterface $authCodeRepository
     */
    public function __construct(OAuthAuthCodeRepositoryInterface $authCodeRepository)
    {
        $this->authCodeRepository = $authCodeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewAuthCode()
    {
        return new AuthCode();
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $this->authCodeRepository->persistNewAuthCode($authCodeEntity);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAuthCode($codeId)
    {
        $this->authCodeRepository->revokeAuthCode($codeId);
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthCodeRevoked($codeId)
    {
        return $this->authCodeRepository->isAuthCodeRevoked($codeId);
    }
}
