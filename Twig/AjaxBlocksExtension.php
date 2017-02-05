<?php
/**
 * This file is part of AjaxBlocksBundle package.
 *
 * (c) Copyright Javier Gil Pereda <javier@gilpereda.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jagilpe\AjaxBlocksBundle\Twig;

/**
 * Twig extension
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
class AjaxBlocksExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        $functions = array();

        $functions[] = new \Twig_SimpleFunction(
            'jgp_ajax_block',
            array($this, 'renderAjaxBlock'),
            array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )
        );

        return $functions;
    }

    /**
     * Renders the ajax block
     *
     * @param \Twig_Environment $environment
     * @param string $controller
     *
     * @return string
     */
    public function renderAjaxBlock(\Twig_Environment $environment, $controller)
    {
        $variables = array(
            'test' => 'test',
            'controllerName' => $controller,
        );

        $template = 'AjaxBlocksBundle::ajax_block.html.twig';
        return $environment->render($template, $variables);
    }
}