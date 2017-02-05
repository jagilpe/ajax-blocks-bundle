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
use Symfony\Bundle\FrameworkBundle\Client;

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

        $blockContainer = $this->getRenderedBlockContainer($client, '/index/'.DefaultController::SIMPLE_BLOCK);
        $blockContent = trim($blockContainer->text());

        $this->assertEquals('Testing block', $blockContent);
    }

    public function testRendersABlockWithParameters()
    {
        $client = $this->createClient();

        $blockContainer = $this->getRenderedBlockContainer($client, '/index/'.DefaultController::BLOCK_WITH_PARAMS);

        $param1 = trim($blockContainer->filter('#param1')->first()->html());
        $param2 = trim($blockContainer->filter('#param2')->first()->html());

        $this->assertEquals('first parameter', $param1);
        $this->assertEquals('second parameter', $param2);
    }

    private function getRenderedBlockContainer(Client $client, $url)
    {
        $crawler = $client->request('GET', $url);
        return $crawler->filter('[data-target="jgp-ajax-block"]')->first();
    }
}