<?php

namespace Next\Components\Debug\Handlers\Controllers;

use Next\View\ViewException;               # View Exception
use Next\Controller\AbstractController;    # Abstract Controller Class

/**
 * Error Controller of Handlers Application
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ErrorController extends AbstractController {

    /**
     * Error Handler Action
     */
    final public function main() {

        // Trying to render a specific Template File

        try {

            $this -> view -> render( sprintf( 'error/code%d', $this -> code ) );

        } catch( ViewException $e ) {

            /**
             * @internal
             *
             * We can't find a Template File for that code.
             *
             * So we'll display a Standard Development Handler
             * because the end user must NOT delete any of the files
             * provided AND he/she must NOT use this built-in Resource
             * to do something different then expected
             */
            \Next\Components\Debug\Handlers::development( $e );
        }
    }
}
