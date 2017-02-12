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

use Jagilpe\AjaxBlocksBundle\Exception\AjaxBlocksErrorCodes;
use Symfony\Component\BrowserKit\Client;

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

    public function testReturnsABlockWithUrlParams()
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

    public function testReturnsABlockWithQueryParams()
    {
        $blockController = 'TestBundle:Default:blockWithQueryParams';
        $params = array(
            'param1' => 'Parameter 1',
            'param2' => 'Parameter 2',
        );

        $expectedBlock = '<span id="param1">Parameter 1</span>';
        $expectedBlock .= '<span id="param2">Parameter 2</span>';

        $responseContent = $this->getResponseContent($blockController, $params);

        $this->assertEquals($expectedBlock, $responseContent->block);
    }

    public function testReturnsABlockWithUrlAndQueryParams()
    {
        $blockController = 'TestBundle:Default:blockWithUrlAndQueryParams';
        $params = array(
            'param1' => 'Parameter 1',
            'param2' => 'Parameter 2',
            'query1' => 'Query parameter 1',
            'query2' => 'Query parameter 2',
        );

        $expectedBlock = '<span id="param1">Parameter 1</span>';
        $expectedBlock .= '<span id="param2">Parameter 2</span>';
        $expectedBlock .= '<span id="query1">Query parameter 1</span>';
        $expectedBlock .= '<span id="query2">Query parameter 2</span>';

        $responseContent = $this->getResponseContent($blockController, $params);

        $this->assertEquals($expectedBlock, $responseContent->block);
    }

    public function testReturnsErrorForWrongControllerNameFormat()
    {
        $blockController = 'RandomString:Testing';

        $expectedError = array(
            'code' => AjaxBlocksErrorCodes::WRONG_CONTROLLER_NAME,
            'error' => 'RandomString:Testing is not a correct controller name.',
        );

        $this->checkErrorResponse($blockController, $expectedError);
    }

    public function testReturnsErrorForWrongBundle()
    {
        $blockController = 'NonExistentBundle:Testing:block';

        $expectedError = array(
            'code' => AjaxBlocksErrorCodes::BUNDLE_DOES_NOT_EXIST,
            'error' => 'Bundle "NonExistentBundle" does not exist or is not loaded.',
        );

        $this->checkErrorResponse($blockController, $expectedError);
    }

    public function testReturnsErrorForNonExistentControllerClass()
    {
        $blockController = 'TestBundle:DoesNotExists:block';
        $controllerClass = 'Jagilpe\AjaxBlocksBundle\Tests\Functional\TestBundle\Controller\DoesNotExistsController';

        $expectedError = array(
            'code' => AjaxBlocksErrorCodes::CONTROLLER_CLASS_DOES_NOT_EXISTS,
            'error' => "Class \"$controllerClass\" does not exist.",
        );

        $this->checkErrorResponse($blockController, $expectedError);
    }

    public function testReturnsErrorForNonExistentController()
    {
        $blockController = 'TestBundle:Default:doesNotExists';
        $controllerClass = 'Jagilpe\AjaxBlocksBundle\Tests\Functional\TestBundle\Controller\DefaultController';

        $expectedError = array(
            'code' => AjaxBlocksErrorCodes::CONTROLLER_CLASS_METHOD_DOES_NOT_EXISTS,
            'error' => "Class \"$controllerClass\" does not have a method called \"doesNotExistsAction\".",
        );

        $this->checkErrorResponse($blockController, $expectedError);
    }

    public function testReturnsErrorForNonPublicController()
    {
        $blockController = 'TestBundle:Default:nonPublic';
        $controllerClass = 'Jagilpe\AjaxBlocksBundle\Tests\Functional\TestBundle\Controller\DefaultController';

        $expectedError = array(
            'code' => AjaxBlocksErrorCodes::CONTROLLER_IS_NOT_CALLABLE,
            'error' => "Method \"nonPublicAction\" of class \"$controllerClass\" is not public or is not callable.",
        );

        $this->checkErrorResponse($blockController, $expectedError);
    }

    private function getResponseContent($blockController, array $params = array(), Client $client = null)
    {
        $paramString = '';
        foreach ($params as $paramName => $paramValue) {
            $paramString .= $paramString !== '' ? "&" : "?";
            $paramString .= $paramName.'='.urlencode($paramValue);
        }

        $uri = '/jgp-ajax-blocks/ajax-block/'.$blockController.$paramString;

        $client = $client ? $client : $this->createClient();
        $client->request('GET', $uri);

        $rawResponseContent = $client->getResponse()->getContent();
        return json_decode($rawResponseContent);
    }

    private function checkErrorResponse($blockController, array $expectedError)
    {
        $client = $this->createClient();
        $responseContent = $this->getResponseContent($blockController, array(), $client);

        $this->assertObjectNotHasAttribute('block', $responseContent);
        $this->assertEquals($expectedError, (array) $responseContent->error);
        $this->assertEquals(406, $client->getResponse()->getStatusCode());
    }

}