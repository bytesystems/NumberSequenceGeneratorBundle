<?php


namespace Bytesystems\NumberGeneratorBundle\Tests;


use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class NumberGeneratorBundleTestsKernel extends Kernel
{

    public function registerBundles(): iterable
    {
        $bundles = [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new \Liip\TestFixturesBundle\LiipTestFixturesBundle(),
            new \Bytesystems\NumberGeneratorBundle\BytesystemsNumberGeneratorBundle(),
        ];

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yaml');
    }
}