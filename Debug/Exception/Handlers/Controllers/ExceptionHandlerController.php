<?php

/**
 * Debug Component Exception Handler Controller Class | Debug\Exception\Handlers\ExceptionController.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Debug\Exception\Handlers\Controllers;

use Next\View\ViewException;               # View Exception
use Next\Controller\AbstractController;    # Abstract Controller Class

/**
 * A \Next\Controller\Controller Class to be used by our custom Exception Handler
 *
 * @package    Next\Debug
 *
 * @uses       Next\View\Exception, Next\Controller\AbstractController
 */
class ExceptionHandlerController extends AbstractController {

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
     * @throws \Next\Debug
     *  A ViewException was caught
     *
     * @see \Next\View\ViewException
     */
    private function trigger( $template ) {

        try {

            $this -> view -> render( $template );

        } catch( ViewException $e ) {

            restore_exception_handler();

            throw new \Next\Debug\Exception( $e -> getMessage() );
        }
    }
}