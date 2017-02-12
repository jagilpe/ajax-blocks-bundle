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

use Jagilpe\AjaxBlocksBundle\Exception\AjaxBlocksErrorCodes;
use Jagilpe\AjaxBlocksBundle\Exception\AjaxBlocksException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Bundle\Bundle;

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
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try {
            $controllerParams = $this->getControllerParams($request, $_ajaxController);
            $controllerResponse = $this->forward($_ajaxController, $controllerParams);
            $responseContent = array(
                'block' => $controllerResponse->getContent(),
            );
        } catch (AjaxBlocksException $ex) {
            $responseContent = array(
                'error' => array(
                    'code' => $ex->getCode(),
                    'error' => $ex->getMessage(),
                ),
            );
            $response->setStatusCode(406);
        } catch (\Exception $ex) {
            $responseContent = array(
                'error' => array(
                    'code' => AjaxBlocksErrorCodes::UNKNOWN_EXCEPTION,
                    'error' => get_class($ex).'('.$ex->getCode().'): '.$ex->getMessage(),
                ),
            );
            $response->setStatusCode(500);
        }

        $response->setContent(json_encode($responseContent));

        return $response;
    }

    /**
     * Searches in the request for the parameters required by the destination controller
     *
     * @param Request $request
     * @param string $controllerString
     *
     * @return array
     *
     * @throws \Exception
     */
    private function getControllerParams(Request $request, $controllerString)
    {
        $controllerInfo = explode(':', $controllerString);

        if (count($controllerInfo) !== 3) {
            throw new AjaxBlocksException(
                "$controllerString is not a correct controller name.",
                AjaxBlocksErrorCodes::WRONG_CONTROLLER_NAME
            );
        }

        try {
            /** @var Bundle $bundle */
            $bundle = $this->get('kernel')->getBundle($controllerInfo[0]);
        } catch (\Exception $ex) {
            throw new AjaxBlocksException(
                "Bundle \"$controllerInfo[0]\" does not exist or is not loaded.",
                AjaxBlocksErrorCodes::BUNDLE_DOES_NOT_EXIST
            );
        }

        $namespace = $bundle->getNamespace();
        $controllerName = $controllerInfo[1].'Controller';
        $controllerMethod = $controllerInfo[2].'Action';
        $className = $namespace.'\\Controller\\'.$controllerName;

        if (!class_exists($className)) {
            throw new AjaxBlocksException(
                "Class \"$className\" does not exist.",
                AjaxBlocksErrorCodes::CONTROLLER_CLASS_DOES_NOT_EXISTS
            );
        }

        $reflectionClass = new \ReflectionClass($className);

        if (!$reflectionClass->hasMethod($controllerMethod)) {
            throw new AjaxBlocksException(
                "Class \"$className\" does not have a method called \"$controllerMethod\".",
                AjaxBlocksErrorCodes::CONTROLLER_CLASS_METHOD_DOES_NOT_EXISTS
            );
        }

        $controller = $reflectionClass->getMethod($controllerMethod);

        $query = $request->query->all();
        $methodParameters = $controller->getParameters();
        if (!$controller->isPublic()) {
            throw new AjaxBlocksException(
                "Method \"$controllerMethod\" of class \"$className\" is not public or is not callable.",
                AjaxBlocksErrorCodes::CONTROLLER_IS_NOT_CALLABLE
            );
        }

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