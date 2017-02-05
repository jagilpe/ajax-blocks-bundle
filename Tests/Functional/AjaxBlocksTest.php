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
 * Ajax Blocks functional tests
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class AjaxBlocksTest extends WebTestCase
{
    public function testRendersTheContentOfTheBlock()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/index/block1');

        $blockContainer = $crawler->filter('.jgp-ajax-block')->first();
        $blockContent = trim($blockContainer->text());

        $this->assertEquals('Testing block', $blockContent);
    }

    public function testRendersTheContentOfAnotherBlock()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/index/block2');
        $blockContainer = $crawler->filter('.jgp-ajax-block')->first();
        $blockContent = trim($blockContainer->text());

        $this->assertEquals('Testing block 2', $blockContent);
    }
}