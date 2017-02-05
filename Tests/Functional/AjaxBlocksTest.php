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

use Jagilpe\AjaxBlocksBundle\Tests\Functional\TestBundle\Controller\DefaultController;

/**
 * Ajax Blocks functional tests
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class AjaxBlocksTest extends WebTestCase
{
    public function testRendersTheContentOfTheBlock()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/index/'.DefaultController::SIMPLE_BLOCK);

        $blockContainer = $crawler->filter('.jgp-ajax-block')->first();
        $blockContent = trim($blockContainer->text());

        $this->assertEquals('Testing block', $blockContent);
    }

    public function testRendersABlockWithParameters()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/index/block-with-params');

        $param1 = trim($crawler->filter('.jgp-ajax-block #param1')->first()->html());
        $param2 = trim($crawler->filter('.jgp-ajax-block #param2')->first()->html());

        $this->assertEquals('first parameter', $param1);
        $this->assertEquals('second parameter', $param2);
    }
}