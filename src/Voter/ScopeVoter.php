<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/2/18
 * Time: 9:53 PM
 */

declare(strict_types=1);


namespace Sonrac\OAuth2\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class ScopeVoter
 * @package Sonrac\OAuth2\Voter
 * //TODO: register voter in OAuthFactory for firewall and add check
 */
class ScopeVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // TODO: Implement supports() method.
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // TODO: Implement voteOnAttribute() method.
    }
}
