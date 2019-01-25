<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/4/18
 * Time: 9:48 PM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Scope;

/**
 * Class Scope.
 */
class Scope
{
    private $scope;

    /**
     * Scope constructor.
     *
     * @param string $scope
     */
    public function __construct(string $scope)
    {
        $this->scope = $scope;
    }

    /**
     * Returns a string representation of the scope.
     *
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }
}
