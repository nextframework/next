<?php

/**
 * Debug Component Exception Handler Controller Class | Component\Debug\Handlers\Controllers\ExceptionController.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Debug\Handlers\Controllers;

use Next\View\ViewException;               # View Exception
use Next\Controller\AbstractController;    # Abstract Controller Class

/**
 * A \Next\Controller\Controller Class to be used by our custom Exception Handler
 *
 * @package    Next\Components\Debug
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
        $this -> trigger( 'exception-development' );
    }

    /**
     * Exception Handler Production Action
     */
    final public function production() {
        $this -> trigger( 'exception-production' );
    }

    /**
     * Exception Handler Rendering Wrapper
     *
     * @param string $template
     *
     * @throws \Next\Components\Debug
     *  A ViewException was caught
     *
     * @see \Next\View\ViewException
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