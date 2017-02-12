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
 * Ajax Blocks initial block rendering functional tests
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class AjaxBlocksTest extends WebTestCase
{
    public function testRendersTheContentOfTheBlock()
    {
        $blockContainer = $this->getRenderedBlockContainer('/index/'.DefaultController::SIMPLE_BLOCK);
        $blockContent = trim($blockContainer->text());

        $this->assertEquals('Testing block', $blockContent);
    }

    public function testRendersABlockWithUrlParameters()
    {
        $blockContainer = $this->getRenderedBlockContainer('/index/'.DefaultController::BLOCK_WITH_PARAMS);

        $param1 = trim($blockContainer->filter('#param1')->first()->html());
        $param2 = trim($blockContainer->filter('#param2')->first()->html());

        $this->assertEquals('first parameter', $param1);
        $this->assertEquals('second parameter', $param2);
    }

    public function testReturnsABlockWithQueryParams()
    {
        $blockContainer = $this->getRenderedBlockContainer('/index/'.DefaultController::BLOCK_WITH_QUERY_PARAMS);

        $param1 = trim($blockContainer->filter('#param1')->first()->html());
        $param2 = trim($blockContainer->filter('#param2')->first()->html());


        $this->assertEquals('first parameter', $param1);
        $this->assertEquals('second parameter', $param2);
    }

    public function testFillsTheAjaxCallbackUrl()
    {
        $params = DefaultController::$params[DefaultController::BLOCK_WITH_PARAMS];

        $blockContainer = $this->getRenderedBlockContainer('/index/'.DefaultController::BLOCK_WITH_PARAMS);

        $ajaxCallbackUrl = $blockContainer->attr('data-src');

        list($url, $queryParams) = $this->explodeUrl($ajaxCallbackUrl);
        list(, $controller) = explode('/jgp-ajax-blocks/ajax-block/', $url);

        $this->assertNotEmpty($ajaxCallbackUrl);
        $this->assertEquals('TestBundle:Default:blockWithParams', $controller);
        foreach ($params as $param => $value) {
            $this->assertEquals($queryParams[$param], $value);
        }
    }

    private function getRenderedBlockContainer($url)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', $url);
        return $crawler->filter('[data-target="jgp-ajax-block"]')->first();
    }

    /**
     * Returns the base url and the query params from a url
     *
     * @param $url
     * @return array
     */
    private function explodeUrl($url)
    {
        $explodedUrl =  explode('?', $url);
        $url = $explodedUrl[0];
        $queryString = isset($explodedUrl[1]) ? $explodedUrl[1] : '';
        $queryParamsRaw = explode('&', $queryString);

        $queryParams = array();
        if ($queryParams !== "") {
            foreach ($queryParamsRaw as $queryParamRaw) {
                $explodedQueryParam = explode('=', $queryParamRaw);
                $param = $explodedQueryParam[0];
                $value = isset($explodedQueryParam[1]) ? $explodedQueryParam[1] : null;
                $queryParams[$param] = urldecode($value);
            }
        }

        return array($url, $queryParams);
    }
}