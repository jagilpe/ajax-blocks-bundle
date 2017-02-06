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
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller responsible for the ajax endpoints
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class AjaxBlockController
{
    /**
     * Gets a request for an ajax block and calls the right controller to build it
     *
     * @param string $_ajaxController
     *
     * @return Response
     */
    public function getAjaxBlockAction($_ajaxController)
    {
        return new Response('');
    }
}