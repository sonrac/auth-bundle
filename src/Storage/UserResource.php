<?php

declare(strict_types=1);

namespace sonrac\Auth\Storage;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Class UserResource.
 */
class UserResource extends AbstractToken
{
    /**
     * UserResource constructor.
     *
     * @throws \InvalidArgumentException
     *
     * @param array $roles
     */
    public function __construct(array $roles = [])
    {
        parent::__construct($roles);

        $this->setAuthenticated(count($roles) > 0);
    }

    /**
     * {@inheritDoc}
     */
    public function getCredentials()
    {
        return '';
    }
}
