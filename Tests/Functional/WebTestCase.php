<?php
/**
 * This file is part of AjaxBlocksBundle package.
 *
 * (c) Copyright Javier Gil Pereda <javier@gilpereda.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jagilpe\AjaxBlocksBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 *  Base WebTestCase for the functional testing
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class WebTestCase extends BaseWebTestCase
{
    /**
     * {@inheritdoc}
     */
    protected static function createKernel(array $options = array())
    {
        $defaultOptions = array(
            'environment' => 'test',
            'debug' => true,
        );

        $options = array_merge($defaultOptions, $options);

        return parent::createKernel($options);
    }

    /**
     * {@inheritdoc}
     */
    protected static function getKernelClass()
    {
        require_once __DIR__.'/app/AppKernel.php';

        return 'Jagilpe\AjaxBlocksBundle\Tests\Functional\app\AppKernel';
    }

}