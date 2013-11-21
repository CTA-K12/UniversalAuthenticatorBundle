<?php

namespace MESD\UniversalAuthenticatorBundle;

use MESD\UniversalAuthenticatorBundle\Security\Factory\UniversalAuthenticationFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class MESDUniversalAuthenticatorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new UniversalAuthenticationFactory());
    }

}
