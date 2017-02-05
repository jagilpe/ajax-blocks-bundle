<?php
/**
 * This file is part of AjaxBlocksBundle package.
 *
 * (c) Copyright Javier Gil Pereda <javier@gilpereda.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jagilpe\AjaxBlocksBundle\Tests\Functional\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Default controller for the functional tests
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class DefaultController extends Controller
{
    const SIMPLE_BLOCK = 'simple-block';
    const BLOCK_WITH_PARAMS = 'block-with-params';

    public function indexAction($block)
    {
        $variables = $this->getVariables($block);

        return $this->render('TestBundle:Default:index.html.twig', $variables);
    }

    public function simpleBlockAction()
    {
        return new Response('Testing block');
    }

    public function blockWithParamsAction($param1, $param2)
    {
        $html = "<span id=\"param1\">$param1</span>";
        $html .= "<span id=\"param2\">$param2</span>";
        return new Response($html);
    }

    private function getVariables($blockName)
    {
        $controllerName = 'TestBundle:Default:';
        switch ($blockName) {
            case self::SIMPLE_BLOCK:
                $variables = array(
                    'controllerName' => $controllerName.'simpleBlock',
                    'controllerParams' => array(),
                );
                break;
            case self::BLOCK_WITH_PARAMS:
                $variables = array(
                    'controllerName' => $controllerName.'blockWithParams',
                    'controllerParams' => array('param1' => 'first parameter', 'param2' => 'second parameter'),
                );
                break;
            default:
                throw new \Exception("Block $blockName does not exists.");
        }

        return $variables;
    }
}