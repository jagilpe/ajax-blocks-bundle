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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

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

    protected function setUp()
    {
        parent::setUp();

        $this->deleteTempDirs();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->deleteTempDirs();
    }

    protected function deleteTempDirs()
    {
        if (!file_exists($dir = sys_get_temp_dir().'/'.Kernel::VERSION)) {
            return;
        }

        $fs = new Filesystem();
        $fs->remove($dir);
    }

}