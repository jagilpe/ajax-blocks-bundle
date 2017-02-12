<?php
/**
 * This file is part of AjaxBlocksBundle package.
 *
 * (c) Copyright Javier Gil Pereda <javier@gilpereda.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jagilpe\AjaxBlocksBundle\Exception;

/**
 * Possible error codes returned in the ajax response
 *
 * @author Javier Gil Pereda <javier@gilpereda.com>
 */
final class AjaxBlocksErrorCodes
{
    const UNKNOWN_EXCEPTION = 0;

    const WRONG_CONTROLLER_NAME = 1;

    const BUNDLE_DOES_NOT_EXIST = 2;

    const CONTROLLER_CLASS_DOES_NOT_EXISTS = 3;

    const CONTROLLER_CLASS_METHOD_DOES_NOT_EXISTS = 4;

    const CONTROLLER_IS_NOT_CALLABLE = 5;
}