<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\TemplateBundle\Tests;

use Mindy\Bundle\TemplateBundle\Finder\BundleTemplateFinder;
use Mindy\Bundle\TemplateBundle\Finder\ThemeTemplateFinder;
use Mindy\Template\Finder\ChainFinder;
use Mindy\Template\Finder\TemplateFinder;
use Mindy\Template\TemplateEngine;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class KernelTest extends KernelTestCase
{
    protected function tearDown()
    {
        (new Filesystem())->remove(__DIR__.'/var');
    }

    protected static function createKernel(array $options = array())
    {
        return new Kernel('dev', true);
    }

    public function testFinders()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $container = $kernel->getContainer();
        $templateEngine = $container->get('templating.engine.mindy');

        $this->assertInstanceOf(TemplateEngine::class, $templateEngine);

        // Bundle finder
        $bundleFinder = $container->get(BundleTemplateFinder::class);
        $this->assertSame([
            __DIR__.'/Bundle/Resources/templates'
        ], $bundleFinder->getPaths());
        $this->assertSame(
            __DIR__.'/Bundle/Resources/templates/test/homepage.html',
            $bundleFinder->find('test/homepage.html')
        );
        $this->assertSame(
            'homepage.html',
            trim($bundleFinder->getContents($bundleFinder->find('test/homepage.html')))
        );

        // Template finder
        $templateFinder = $container->get(TemplateFinder::class);
        $this->assertSame([
            __DIR__.'/Resources/templates'
        ], $templateFinder->getPaths());
        $this->assertSame(
            __DIR__.'/Resources/templates/test/homepage.html',
            $templateFinder->find('test/homepage.html')
        );
        $this->assertSame(
            'homepage.html',
            trim($templateFinder->getContents($templateFinder->find('test/homepage.html')))
        );

        // Theme finder
        $themeFinder = $container->get(ThemeTemplateFinder::class);
        $this->assertSame([
            __DIR__.'/Resources/themes/default/templates'
        ], $themeFinder->getPaths());
        $this->assertSame(
            __DIR__.'/Resources/themes/default/templates/test/homepage.html',
            $themeFinder->find('test/homepage.html')
        );
        $this->assertSame(
            'homepage.html',
            trim($themeFinder->getContents($templateFinder->find('test/homepage.html')))
        );

        $themeFinder->setTheme('attic');
        $this->assertSame([
            __DIR__.'/Resources/themes/attic/templates'
        ], $themeFinder->getPaths());

        $themeFinder->setTheme('rise');
        $this->assertSame([
            __DIR__.'/Resources/themes/rise/templates'
        ], $themeFinder->getPaths());

        $chainFinder = $container->get(ChainFinder::class);
        $this->assertSame(
            __DIR__.'/Resources/templates/test/only_in_templates.html',
            $chainFinder->find('test/only_in_templates.html')
        );
        $this->assertSame(
            __DIR__.'/Resources/themes/rise/templates/test/only_rise_theme.html',
            $chainFinder->find('test/only_rise_theme.html')
        );
    }
}
