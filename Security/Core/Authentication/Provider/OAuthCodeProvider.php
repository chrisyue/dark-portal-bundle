<?php

namespace Chrisyue\Bundle\DarkPortalBundle\Security\Core\Authentication\Provider;

use Chrisyue\Bundle\DarkPortalBundle\Security\Core\Authentication\Token\OAuthCodeToken;
use Chrisyue\Bundle\DarkPortalBundle\Security\Core\User\OAuthCodeUserProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class OAuthCodeProvider implements AuthenticationProviderInterface
{
    const URL_AUTH_FORMAT = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';

    private $guzzle;
    private $options;
    private $userProvider;
    private $providerKey;

    public function __construct(OAuthCodeUserProvider $userProvider, array $options, $providerKey)
    {
        $this->guzzle = new Client();
        $this->options = $options;
        $this->userProvider = $userProvider;
        $this->providerKey = $providerKey;
    }

    public function authenticate(TokenInterface $token)
    {
        $openid = $token->getUsername();
        if (!empty($openid)) {
            $user = $this->userProvider->loadUserByUsername($openid);

            return new OAuthCodeToken($user, null, $this->providerKey, $user->getRoles());
        }

        $appid = $this->options['appid'];
        $secret = $this->options['secret'];
        $code = $token->getCredentials();
        $url = sprintf(self::URL_AUTH_FORMAT, $appid, $secret, $code);

        try {
            $json = json_decode($this->guzzle->get($url)->getBody(), true);
        } catch (ClientException $ex) {
            throw new AuthenticationException('OAuth code is not valid');
        }

        if (!empty($json['errcode'])) {
            throw new AuthenticationException($json['errmsg']);
        }

        $user = $this->userProvider->loadUserByCredentials($json, $this->options['scope'] === 'snsapi_userinfo');

        return new OAuthCodeToken($user, null, $this->providerKey, $user->getRoles());
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof OAuthCodeToken && $token->getProviderKey() === $this->providerKey;
    }
}
