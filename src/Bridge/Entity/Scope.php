<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/15/18
 * Time: 11:45 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Entity;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * Class Scope
 * @package Sonrac\OAuth2\Bridge\Entity
 */
class Scope implements ScopeEntityInterface
{
    use EntityTrait;

    /**
     * Scope constructor.
     * @param $identifier
     */
    public function __construct($identifier)
    {
        $this->$identifier = $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }
}
