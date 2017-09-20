<?php

/**
 * Debug Component Exception Handler Controller Class | Exception\Handlers\Controllers\ExceptionController.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Handlers\Controllers;

use Next\View\ViewException;               # View Exception
use Next\Controller\AbstractController;    # Abstract Controller Class

/**
 * A \Next\Controller\Controller Class to be used by our custom Exception Handler
 *
 * @package    Next\Exception
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

        try {

            $this -> view -> render( 'exception-development' );

        } catch( ViewException $e ) {

            restore_exception_handler();

            throw new \Next\Exception( $e -> getMessage() );
        }
    }

    /**
     * Exception Handler Production Action
     */
    final public function production() {

        try {

            $this -> view -> render( 'exception-production' );

        } catch( ViewException $e ) {

            restore_exception_handler();

            throw new \Next\Exception( $e -> getMessage() );
        }
    }
}