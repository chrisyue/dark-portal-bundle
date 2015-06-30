<?php

namespace Chrisyue\Bundle\DarkPortalBundle\Security\Core\User;

use Symfony\Component\Security\Core\User\UserInterface;

class OAuthCodeUser implements UserInterface
{
    private $openid;

    private $accessToken;

    private $attributes;

    private $credentials;

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

    public function getCredential($key)
    {
        return $this->credentials[$key];
    }

    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;

        return $this;
    }

    public function eraseCredentials()
    {
        $this->credentials = [];

        return $this;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getAttribute($key)
    {
        return $this->attributes[$key];
    }
}
