<?php
/**
 * This file is part of AjaxBlocksBundle package.
 *
 * (c) Copyright Javier Gil Pereda <javier@gilpereda.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jagilpe\AjaxBlocksBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Intercepts the request of an ajax block and reorders the parameters as
 * the target controller is expecting them
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class AjaxBlockControllerEventListener
{
    const JGP_AJAX_BLOCK_TAG = '_jgp_ajax_block';

    /**
     * Loads in the request the parameters that the controller should receive as query parameters
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        if($request->attributes->get(self::JGP_AJAX_BLOCK_TAG)) {
            $queryParams = array();

            $controller = $event->getController();
            $reflectionMethod = is_array($controller)
                ? new \ReflectionMethod($controller[0], $controller[1])
                : new \ReflectionFunction($controller);

            $controllerParameters = array_map(function(\ReflectionParameter $parameter) {
                return $parameter->getName();
            }, $reflectionMethod->getParameters());

            $parameters = $request->attributes->all();
            foreach ($parameters as $paramName => $paramValue) {
                if (!in_array($paramName, $controllerParameters)) {
                    $queryParams[$paramName] = $paramValue;
                }
            }

            $request->query->add($queryParams);
        }
    }
}