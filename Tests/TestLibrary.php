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

use Mindy\Template\Library\AbstractLibrary;

class TestLibrary extends AbstractLibrary
{
    /**
     * @return array
     */
    public function getHelpers()
    {
        return [
            'foo' => function () {
                return 'bar';
            },
        ];
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return [];
    }
}
