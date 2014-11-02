<?php

namespace Next\Components\Debug\Handlers\Controllers;

use Next\View\ViewException;               # View Exception
use Next\Controller\AbstractController;    # Abstract Controller Class

/**
 * Exception Controller of Handlers Application
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ExceptionController extends AbstractController {

    /**
     * Additional Initialization. Must be overwritten
     */
    public function init() {

        // Assigning Exception Variable

        $this -> e = $this -> e;
    }

    /**
     * Exception Handler Development Action
     */
    final public function development() {
        $this -> trigger( 'exception/development' );
    }

    /**
     * Exception Handler Production Action
     */
    final public function production() {
        $this -> trigger( 'exception/production' );
    }

    /**
     * Exception Handler Rendering Wrapper
     *
     * @param string $template
     *
     * @throws Next\Components\Debug
     *  A ViewException was caught
     *
     * @see Next\View\ViewException
     */
    private function trigger( $template ) {

        try {

            $this -> view -> render( $template );

        } catch( ViewException $e ) {

            restore_exception_handler();

            throw new \Next\Components\Debug\Exception( $e -> getMessage() );
        }
    }
}