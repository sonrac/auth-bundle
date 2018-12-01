<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/4/18
 * Time: 9:48 PM
 */

namespace sonrac\Auth\Security\Scope;

/**
 * Class Scope
 * @package sonrac\Auth\Security\Scope
 */
class Scope
{
    private $scope;

    /**
     * Scope constructor.
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
