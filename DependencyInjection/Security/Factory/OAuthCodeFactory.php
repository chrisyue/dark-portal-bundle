<?php

namespace Chrisyue\Bundle\DarkPortalBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class OAuthCodeFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.oauth_code.'.$id;

        $container->setDefinition($providerId, new DefinitionDecorator('chrisyue_dark_portal.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProvider))
            ->replaceArgument(1, $config)
            ->replaceArgument(2, $id);

        $listenerId = 'security.authentication.listener.oauth_code.'.$id;
        $container->setDefinition($listenerId, new DefinitionDecorator('chrisyue_dark_portal.security.authentication.listener'))
            ->replaceArgument(2, $config)
            ->replaceArgument(3, $id);

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'oauth_code';
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('appid')->end()
                ->scalarNode('secret')->end()
                ->scalarNode('scope')->end()
                ->scalarNode('code_endpoint')->end()
            ->end();
    }
}
