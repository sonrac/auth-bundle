<?php

declare(strict_types=1);

namespace sonrac\Auth\Entity;

use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * Interface ClientInterface
 * @package sonrac\Auth\Entity
 */
interface ClientInterface extends ClientEntityInterface
{
    /**
     * @return string
     */
    public function getSecret(): string;
}
