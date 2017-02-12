<?php
/**
 * This file is part of AjaxBlocksBundle package.
 *
 * (c) Copyright Javier Gil Pereda <javier@gilpereda.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jagilpe\AjaxBlocksBundle\Tests\EventListener;

use Jagilpe\AjaxBlocksBundle\EventListener\AjaxBlockControllerEventListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Unit tests for the AjaxBlockControllerEventListener class
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class AjaxBlockControllerEventListenerTest extends TestCase
{
    public function testLoadsQueryParametersForClassBasedController()
    {
        $eventListener = new AjaxBlockControllerEventListener();

        $parameters = array(
            '_jgp_ajax_block' => true,
            'param1' => 'Param1',
            'param2' => 'Param2',
            'query1' => 'Query param 1',
            'query2' => 'Query param 2',
        );

        $kernelMock = $this->createMock(Kernel::class);
        $controller = array(new TestController(), 'testingAction');
        $request = new Request();
        $request->attributes->add($parameters);

        $event = new FilterControllerEvent($kernelMock, $controller, $request, 'GET');

        $eventListener->onKernelController($event);

        $request = $event->getRequest();

        $queryParams = $request->query->all();
        $this->assertArrayHasKey('query1', $queryParams);
        $this->assertArrayHasKey('query2', $queryParams);
    }

    public function testLoadsQueryParametersForCallable()
    {
        $eventListener = new AjaxBlockControllerEventListener();

        $parameters = array(
            '_jgp_ajax_block' => true,
            'param1' => 'Param1',
            'param2' => 'Param2',
            'query1' => 'Query param 1',
            'query2' => 'Query param 2',
            'query3' => 'Query param 3',
        );

        $kernelMock = $this->createMock(Kernel::class);
        $controller = function($param1, $param2) {};
        $request = new Request();
        $request->attributes->add($parameters);

        $event = new FilterControllerEvent($kernelMock, $controller, $request, 'GET');

        $eventListener->onKernelController($event);

        $request = $event->getRequest();

        $queryParams = $request->query->all();
        $this->assertArrayHasKey('query1', $queryParams);
        $this->assertArrayHasKey('query2', $queryParams);
        $this->assertArrayHasKey('query3', $queryParams);
    }

    public function testDoesNotChangeNotAjaxBlocksRequests()
    {
        $eventListener = new AjaxBlockControllerEventListener();

        $parameters = array(
            'param1' => 'Param1',
            'param2' => 'Param2',
            'query1' => 'Query param 1',
            'query2' => 'Query param 2',
        );

        $kernelMock = $this->createMock(Kernel::class);
        $controller = function($param1, $param2) {};
        $request = new Request();
        $request->attributes->add($parameters);

        $event = new FilterControllerEvent($kernelMock, $controller, $request, 'GET');

        $eventListener->onKernelController($event);

        $request = $event->getRequest();

        $queryParams = $request->query->all();
        $this->assertArrayNotHasKey('query1', $queryParams);
        $this->assertArrayNotHasKey('query2', $queryParams);
    }
}

class TestController
{
    public function testingAction($param1, $param2)
    {

    }
}