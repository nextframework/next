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
    final public function status() {

        // Trying to render a specific Template File

        try {

            $this -> view -> render( sprintf( 'error/status/code%d', $this -> code ) );

        } catch( ViewException $e ) {

            \Next\Components\Debug\Handlers::development( $e );
        }
    }

    final public function error() {

        $this -> view -> assign( 'e', $this -> e ) -> render( 'error/error.phtml' );
    }
}
