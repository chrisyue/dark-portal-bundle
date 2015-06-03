<?php

namespace Chrisyue\Bundle\DarkPortalBundle;

use Chrisyue\Bundle\DarkPortalBundle\DependencyInjection\Security\Factory\OAuthCodeFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ChrisyueDarkPortalBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new OAuthCodeFactory());
    }
}
