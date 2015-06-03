<?php

namespace Chrisyue\Bundle\DarkPortalBundle\Security\Http\Firewall;

use Chrisyue\Bundle\DarkPortalBundle\Security\Core\Authentication\Token\OAuthCodeToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class OAuthCodeListener implements ListenerInterface
{
    private $tokenStorage;
    private $authenticationManager;
    private $providerKey;
    private $options;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        array $options,
        $providerKey
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->options = $options;
        $this->providerKey = $providerKey;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->query->has('code')) {
            $code = $request->query->get('code');
            $token = new OAuthCodeToken('', $code, $this->providerKey);
        } else {
            $redirectUri = sprintf('%s?%s', $this->options['code_endpoint'], http_build_query([
                'redirect_uri' => $request->getUri(),
                'scope' => $this->options['scope'],
            ]));
            $event->setResponse(new RedirectResponse($redirectUri));

            return;
        }

        $token = $this->authenticationManager->authenticate($token);
        $this->tokenStorage->setToken($token);
    }
}
