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

/**
 * Default test to check the configuration of the functional test environment
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class DefaultTest extends WebTestCase
{
    public function testCanGetIndex()
    {
        $client = $this->createClient();

        $client->request('GET', '/index');

        $this->assertEquals('testing', $client->getResponse()->getContent());
    }

}