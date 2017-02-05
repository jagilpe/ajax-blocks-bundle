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
    public function indexAction($block)
    {
        $variables = array(
            'controller' => 'TestBundle:Default:'.$block
        );

        return $this->render('TestBundle:Default:index.html.twig', $variables);
    }

    public function block1Action()
    {
        return new Response('Testing block');
    }

    public function block2Action()
    {
        return new Response('Testing block 2');
    }
}