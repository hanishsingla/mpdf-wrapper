<?php

namespace Symfgenus\MpdfWrapper\Tests;

use Symfgenus\MpdfWrapper\MpdfWrapperBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class FunctionalTest extends TestCase
{
    public function testServiceWiring()
    {
        $kernel = new SymfgenusTranslationUiTestingKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();
    }

}

class SymfgenusTranslationUiTestingKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new MpdfWrapperBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {

    }

}
