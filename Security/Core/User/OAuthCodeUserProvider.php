<?php

namespace Chrisyue\Bundle\DarkPortalBundle\Security\Core\User;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OAuthCodeUserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        return new OAuthCodeUser($username);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(':(');
        }

        return $user;
    }

    public function supportsClass($class)
    {
        return 'Chrisyue\\Bundle\\DarkPortalBundle\\Security\\Core\\User\\OAuthCodeUser' === $class;
    }
}
