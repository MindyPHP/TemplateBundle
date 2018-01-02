<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\TemplateBundle\Tests;

use Mindy\Bundle\TemplateBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function testConfiguration()
    {
        $configuration = new Configuration();

        $node = $configuration->getConfigTreeBuilder()->buildTree();

        $this->assertEquals(
            [
                'path' => '%kernel.root_dir%/Resources/templates',
                'theme' => 'default',
                'mode' => 0,
                'exception_handler' => true,
            ],
            $node->finalize($node->normalize([]))
        );
    }
}
