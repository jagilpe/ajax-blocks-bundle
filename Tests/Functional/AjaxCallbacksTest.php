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
 * Functional tests for the ajax update responses
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class AjaxCallbacksTest extends WebTestCase
{
    public function testReturnsASimpleBlock()
    {
        $blockController = 'TestBundle:Default:simpleBlock';

        $responseContent = $this->getResponseContent($blockController);

        $this->assertEquals('Testing block', $responseContent->block);
    }

    public function testReturnsABlockWithParams()
    {
        $blockController = 'TestBundle:Default:blockWithParams';
        $params = array(
            'param1' => 'Parameter 1',
            'param2' => 'Parameter 2',
        );

        $expectedBlock = '<span id="param1">Parameter 1</span>';
        $expectedBlock .= '<span id="param2">Parameter 2</span>';

        $responseContent = $this->getResponseContent($blockController, $params);

        $this->assertEquals($expectedBlock, $responseContent->block);
    }

    private function getResponseContent($blockController, array $params = array())
    {
        $paramString = '';
        foreach ($params as $paramName => $paramValue) {
            $paramString .= $paramString !== '' ? "&" : "?";
            $paramString .= $paramName.'='.urlencode($paramValue);
        }

        $uri = '/jgp-ajax-blocks/ajax-block/'.$blockController.$paramString;

        $client = $this->createClient();
        $client->request('GET', $uri);

        $rawResponseContent = $client->getResponse()->getContent();
        return json_decode($rawResponseContent);
    }

}