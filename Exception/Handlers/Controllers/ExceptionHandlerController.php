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

/**
 * Exception Class(es)
 */
use Next\Exception\Exception;
use Next\Exception\Exceptions\FatalException;
use Next\Exception\Exceptions\InvalidArgumentException;
use Next\Exception\Exceptions\RuntimeException;
use Next\Exception\Exceptions\AccessViolationException;

use Next\Controller\Controller;    # Abstract Controller Class

/**
 * Page Controller Class to be used by our custom Exception Handler
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exception
 *             Next\Exception\Exceptions\FatalException
 *             Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Exception\Exceptions\RuntimeException
 *             Next\Exception\Exceptions\AccessViolationException
 *             Next\Controller\Controller
 */
class ExceptionHandlerController extends Controller {

    /**
     * Additional Initialization. Must be overwritten
     */
    protected function init() : void {

        // Assigning Exception Variable

        $this -> e = $this -> e;
    }

    /**
     * Exception Handler Development Action
     */
    final public function development() : void {

        try {

            $this -> view -> render( 'exception-development' );

        } catch( InvalidArgumentException | RuntimeException | AccessViolationException | Exception $e ) {

            /**
             * @internal
             *
             * These are all Exceptions thrown by Next\View\ViewException,
             * but if everything else fails, the base Exception is also
             * there as fallback
             */
            restore_exception_handler();

            throw new FatalException( $e -> getMessage() );
        }
    }

    /**
     * Exception Handler Production Action
     */
    final public function production() : void {

        try {

            $this -> view -> render( 'exception-production' );

        } catch( InvalidArgumentException | RuntimeException | AccessViolationException | Exception $e ) {

            restore_exception_handler();

            throw new FatalException( $e -> getMessage() );
        }
    }
}