<?php
/**
 * This file is part of AjaxBlocksBundle package.
 *
 * (c) Copyright Javier Gil Pereda <javier@gilpereda.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jagilpe\AjaxBlocksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller responsible for the ajax endpoints
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class AjaxBlockController extends Controller
{
    /**
     * Gets a request for an ajax block and calls the right controller to build it
     *
     * @param Request $request
     * @param string $_ajaxController
     *
     * @return Response
     */
    public function getAjaxBlockAction(Request $request, $_ajaxController)
    {
        $controllerParams = $this->getControllerParams($request, $_ajaxController);

        $controllerResponse = $this->forward($_ajaxController, $controllerParams);

        $responseContent = array(
            'block' => $controllerResponse->getContent(),
        );

        $response = new Response(json_encode($responseContent));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Searches in the request for the parameters required by the destination controller
     *
     * @param Request $request
     * @param string $controllerString
     *
     * @return array
     */
    private function getControllerParams(Request $request, $controllerString)
    {
        $controllerInfo = explode(':', $controllerString);

        $bundle = $controllerInfo[0];
        $namespace = $this->get('kernel')->getBundle($bundle)->getNamespace();
        $controllerName = $controllerInfo[1].'Controller';
        $controllerMethod = $controllerInfo[2].'Action';
        $className = $namespace.'\\Controller\\'.$controllerName;

        $reflectionClass = new \ReflectionClass($className);

        $controller = $reflectionClass->getMethod($controllerMethod);

        $query = $request->query->all();
        $methodParameters = $controller->getParameters();

        $parameters = array();
        foreach ($methodParameters as $methodParameter) {
            $parameterName = $methodParameter->getName();
            if ('request' === $parameterName) continue;

            if (isset($query[$parameterName])) {
                $parameters[$parameterName] = $query[$parameterName];
            }
        }

        return $parameters;
    }
}