<?php

namespace Bytesystems\NumberGeneratorBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterTokenHandlersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $registry = $container->getDefinition('bytesystems_number_generator.token.token_handler_registry');

        $services = $container->findTaggedServiceIds('bytesystems_number_generator.token_handler');
        foreach ($services as $id => $service) {
            $registry->addMethodCall('addHandler',[new Reference($id)]);
        }
    }
}