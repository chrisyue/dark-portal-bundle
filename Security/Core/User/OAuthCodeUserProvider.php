<?php

namespace Chrisyue\Bundle\DarkPortalBundle\Security\Core\User;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use GuzzleHttp\Client;

class OAuthCodeUserProvider implements UserProviderInterface
{
    private $guzzle;

    const URL_USER_INFO = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN';

    public function __construct()
    {
        $this->guzzle = new Client();
    }

    public function loadUserByCredentials(array $credentials, $shouldLoadUserInfo = true)
    {
        $openid = $credentials['openid'];
        $accessToken = $credentials['access_token'];

        $user = $this->loadUserByUsername($openid);
        $user->setCredentials($credentials);

        if ($shouldLoadUserInfo) {
            $url = sprintf(self::URL_USER_INFO, $accessToken, $openid);
            $json = $this->guzzle->get($url)->json();

            $user->setAttributes($json);
        }

        return $user;
    }

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
