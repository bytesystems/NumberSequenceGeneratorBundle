<?php


namespace Bytesystems\NumberGeneratorBundle\DependencyInjection;


use Bytesystems\NumberGeneratorBundle\Token\TokenHandlerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class BytesystemsNumberGeneratorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs,$container);
        $config = $this->processConfiguration($configuration,$configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.xml');

        if (method_exists($container,'registerForAutoconfiguration')) {
            $container->registerForAutoconfiguration(TokenHandlerInterface::class)->addTag('bytesystems.number_generator_bundle.token_handler');
        }
    }
}