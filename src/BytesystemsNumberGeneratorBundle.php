<?php


namespace Bytesystems\NumberGeneratorBundle;

use Bytesystems\NumberGeneratorBundle\DependencyInjection\Compiler\RegisterTokenHandlersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BytesystemsNumberGeneratorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterTokenHandlersPass());
        parent::build($container);
    }
}