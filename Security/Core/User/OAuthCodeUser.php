<?php

namespace Chrisyue\Bundle\DarkPortalBundle\Security\Core\User;

use Symfony\Component\Security\Core\User\UserInterface;

class OAuthCodeUser implements UserInterface
{
    private $openid;

    public function __construct($openid)
    {
        $this->openid = $openid;
    }

    public function getUsername()
    {
        return $this->openid;
    }

    public function getRoles()
    {
        return ['ROLE_OAUTH_USER'];
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}
