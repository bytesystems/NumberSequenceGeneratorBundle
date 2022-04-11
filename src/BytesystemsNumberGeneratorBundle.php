<?php


namespace Bytesystems\NumberGeneratorBundle;

use Bytesystems\NumberGeneratorBundle\DependencyInjection\Compiler\RegisterTokenHandlersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BytesystemsNumberGeneratorBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterTokenHandlersPass());
        parent::build($container);
    }
}