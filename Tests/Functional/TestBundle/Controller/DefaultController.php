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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Default controller for the functional tests
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class DefaultController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function indexAction()
    {
        return new Response('testing');
    }
}