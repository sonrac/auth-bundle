<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/16/18
 * Time: 2:16 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Sonrac\OAuth2\Adapter\Repository\UserRepositoryInterface as OAuthUserRepositoryInterface;
use Sonrac\OAuth2\Bridge\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserRepository
 * @package Sonrac\OAuth2\Bridge\Repository
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var \Sonrac\OAuth2\Adapter\Repository\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserRepository constructor.
     * @param \Sonrac\OAuth2\Adapter\Repository\UserRepositoryInterface $userRepository
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(OAuthUserRepositoryInterface $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $user = $this->userRepository->findUserByUsername($username);

        if (null === $user || false === $this->passwordEncoder->isPasswordValid($user, $password)) {
            throw OAuthServerException::invalidCredentials();
        }

        //TODO: add optional validation for grant type and client.

        return new User($user->getIdentifier());
    }
}
