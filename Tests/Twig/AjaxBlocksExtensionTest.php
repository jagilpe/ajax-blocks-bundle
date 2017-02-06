<?php
/**
 * This file is part of AjaxBlocksBundle package.
 *
 * (c) Copyright Javier Gil Pereda <javier@gilpereda.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jagilpe\AjaxBlocksBundle\Tests\Twig;
use Jagilpe\AjaxBlocksBundle\Twig\AjaxBlocksExtension;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the AjaxBundleExtension class
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class AjaxBundleExtensionTest extends TestCase
{
    public function testRegistersBlockFunction()
    {
        $extension = new AjaxBlocksExtension();

        $functions = array_filter($extension->getFunctions(), function(\Twig_SimpleFunction $function) {
            return $function->getName() === 'jgp_ajax_block';
        });

        $this->assertEquals(1, count($functions));
    }

    public function testRendersAjaxBlock()
    {
        $extension = new AjaxBlocksExtension();

        $twigEnvironment = $this->getMockBuilder(\Twig_Environment::class)
            ->setMethods(['render'])
            ->getMock();

        $expectedVariables = array(
            'controllerName' => 'TestingBundle:Test:block'
        );

        $twigEnvironment->expects($this->once())
            ->method('render')
            ->with(
                'AjaxBlocksBundle::ajax_block.html.twig',
                $this->callback(function($variables) use ($expectedVariables) {
                    $matches = true;
                    foreach ($expectedVariables as $key => $value) {
                        if (!isset($variables[$key]) || $variables[$key] !== $value) {
                            return false;
                        }
                    }
                    return $matches;
                }));

        $extension->renderAjaxBlock($twigEnvironment, 'TestingBundle:Test:block');
    }

    public function testPassesTheBlockParameters()
    {
        $extension = new AjaxBlocksExtension();

        $twigEnvironment = $this->getMockBuilder(\Twig_Environment::class)
            ->setMethods(['render'])
            ->getMock();

        $expectedVariables = array(
            'controllerName' => 'TestingBundle:Test:block',
            'controllerParams' => array('param1' => 'parameter 1', 'param2' => 'parameter 2'),
            'routeName' => 'jgp_ajax_block',
            'routeParams' => array(
                '_ajaxController' => 'TestingBundle:Test:block',
                'param1' => 'parameter 1',
                'param2' => 'parameter 2',
            ),
        );

        $twigEnvironment->expects($this->once())
            ->method('render')
            ->with(
                'AjaxBlocksBundle::ajax_block.html.twig',
                $this->callback($this->getArrayMatcher($expectedVariables)));

        $extension->renderAjaxBlock(
            $twigEnvironment,
            'TestingBundle:Test:block',
            array('param1' => 'parameter 1', 'param2' => 'parameter 2'));
    }

    private function getArrayMatcher($expectedValues)
    {
        return function($values) use ($expectedValues) {
            $matches = true;

            foreach ($expectedValues as $key => $value) {
                if (!isset($values[$key])) {
                    return false;
                }

                if (is_array($value)) {
                    foreach ($value as $subkey => $subvalue) {
                        if (!isset($values[$key][$subkey]) || $values[$key][$subkey] !== $subvalue) {
                            return false;
                        }
                    }
                } else {
                    if ($values[$key] !== $value) {
                        return false;
                    }
                }

            }

            return $matches;
        };
    }

}