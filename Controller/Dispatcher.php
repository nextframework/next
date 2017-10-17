<?php

/**
 * Controller Dispatcher Class | Controller\Dispatcher.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller;

/**
 * Exception Class(es)
 */
use Next\Exception\Exception;
use Next\Exception\Exceptions\BadMethodCallException;
use Next\View\ViewException;

use Next\Application\Application;       # Application Interface

use Next\Components\Object;             # Object Class
use Next\Exception\ExceptionHandler;    # Exceptions Handlers Class
use Next\Components\Parameter;          # Parameter Class
use Next\HTTP\Response;                 # HTTP Response Class

/**
 * The Controller Dispatcher reflects over Application Controllers,
 * configuring their associated `\Next\HTTP\Request` and returns their
 * associated `\Next\HTTP\Response`
 *
 * Here is also where the Error Standardization Concept takes place,
 * virtually resending the Response by invoking the callback defined
 * within the `\Next\Controller\ControllerException` thrown
 *
 * This handles even nested Exceptions but it's not bullet proof
 * against infinity recursion created by the developer, redirecting
 * the Response Flow too many times to the point soon or later the
 * start point is reached again
 *
 * @package    Next\Controller
 */
class Dispatcher extends Object {

    /**
     * Flag to condition whether or not the Response Object
     * will be returned or not
     *
     * @var boolean $shouldReturn
     */
    protected $shouldReturn = FALSE;

    /**
     * Dispatching Control Flag
     *
     * @var boolean $isDispatched
     */
    protected $isDispatched = FALSE;

    /**
     * Dispatches the Controller
     *
     * @param \Next\Application\Application $application
     *  Application to Configure
     *
     * @param \Next\Components\Parameter $data
     *  Parameters to Configure Application
     *
     * @return \Next\HTTP\Response
     *  Response Object
     *
     * @throws \Next\Exception\Exceptions\BadMethodCallException
     *  ReflectionException was caught
     */
    public function dispatch( Application $application, Parameter $data ) {

        $response = $application -> getResponse();

        try {

            $this -> setDispatched( TRUE );

            // Adding Request Params

            $application -> getRequest() -> setQuery(
                (array) $data -> getParameters() -> params -> getParameters()
            );

            // Calling Action from Controller of defined Application

            $reflector = new \ReflectionMethod( $data -> controller, $data -> method );

            $reflector -> invoke( new $data -> controller( $application ) );

            return $response;

        } catch( \ReflectionException $e ) {

            throw new BadMethodCallException(

                sprintf(

                    'Unable to dispatch <em>%s::%s()</em>

                    The following error has been returned: %s',

                    $data -> controller, $data -> method, $e -> getMessage()
                )
            );

        } catch( ControllerException $e ) {

            /**
             * @internal
             *
             * ControllerException come from Application Controllers
             * and, as part of Error Standardization Concept, should be
             * thrown when something is wrong
             *
             * E.g.: Database Query results in FALSE instead of a RowSet Object
             *
             * Doesn't matter the level of DEVELOPMENT MODE Constant,
             * we'll try to create a Template Variable and virtually
             * re-send the Response, by invoking a callback previously
             * associated to ControllerException object
             *
             * Now, in Template View, a special variable named
             * `__EXCEPTION__` will be available with Exception Message
             * just like if it was assigned from Controller context
             *
             * If the Exception caught is not a severe error, like a
             * successful query returning no results, instead of the
             * `__EXCEPTION__` a template variable named `__INFO__`
             * will be created
             *
             * If the assignment or rendering fails (unlikely),
             * the Production Handler will be used as fallback
             */
            try {

                $response -> addHeader( $e -> getResponseCode() );

            } catch( FieldsException $e ) {}

            try {

                return $this -> handleExceptionCallback( $application, $e );

            } catch( ViewException $e ) {

                ExceptionHandler::production( $e );
            }

        } catch( ViewException $e ) {

            /**
             * @internal
             *
             * Catching ViewException grants a nice view for any sort of
             * errors triggered by \Next\View\View concrete classes, specially
             * when they come from Magic Methods which is directly related
             * to Template Variables usage.
             *
             * And by forcing a Development Handler we warn lazy programmers
             * they are doing the wrong thing, like trying to hide the error ^_^
             */
            if( ob_get_length() ) {

                // We want ONLY the Exception Template

                ob_end_clean();
            }

            ExceptionHandler::development( $e );
        }
    }

    // Accessory Methods

    /**
     * Set Response as Dispatched
     *
     * @param boolean $flag
     *  Defines whether or not the Controller was already dispatched
     *
     * @return \Next\Controller\Dispatcher
     *  Dispatcher Instance (Fluent Interface)
     */
    public function setDispatched( $flag ) {

        $this -> isDispatched = (bool) $flag;

        return $this;
    }

    /**
     * Checks if a Controller was Dispatched
     *
     * @return boolean
     *  TRUE if a Controller was already Dispatched and FALSE otherwise
     */
    public function isDispatched() {
        return $this -> isDispatched;
    }

    /**
     * Change state of dispatching return conditional flag
     *
     * @param boolean $flag
     *  New state for the flag
     *
     * @return \Next\Controller\Dispatcher
     *  Dispatcher Instance (Fluent Interface)
     */
    public function returnResponse( $flag ) {

        $this -> shouldReturn = (bool) $flag;

        return $this;
    }

    /**
     * Get current state of dispatching returning conditional flag
     *
     * @return boolean
     *  Dispatching returning flag value
     */
    public function shouldReturn() {
        return $this -> shouldReturn;
    }

    // Auxiliary Methods

    /**
     * Handles a dispatchable Exception Callback
     *
     * @param \Next\Application\Application $application
     *  Application Object being dispatched
     *
     * @param \Next\Exception\Exception $e
     *  Exception thrown
     */
    private function handleExceptionCallback( Application $application, Exception $e ) {

        try {

            /**
             * @internal
             *
             * List of non-error HTTP Codes
             *
             * @var $codes array
             */
            $codes = [

                Response::OK, Response::CREATED, Response::ACCEPTED,
                Response::NO_CONTENT, Response::PARTIAL_CONTENT, Response::NOT_MODIFIED
            ];

            $view = $application -> getView();

            if( in_array( $e -> getResponseCode(), $codes ) ) {

                $view -> assign( '__INFO__', $e -> getMessage() );

            } else {

                $view -> assign( '__EXCEPTION__', $e -> getMessage() );
            }

            $callback = $e -> getCallback();

            if( is_callable( $callback ) ) {
                return call_user_func( $callback );
            }

            switch( count( $callback ) ) {

                case 0:
                    // No callback, do nothing
                break;
                case 1:  call_user_func( $callback[ 0 ] ); break;
                case 2:  call_user_func( $callback[ 0 ], $callback[ 1 ] ); break;
                default:

                    if( ! is_callable( $callback[ 0 ] ) ) {

                        ExceptionHandler::development(
                            new Exception( 'Exception callbacks must be callable' )
                        );
                    }

                    call_user_func_array( $callback[ 0 ], array_slice( $callback, 1 ) );

                break;
            }

        } catch( ControllerException $e ) {

            /**
             * @internal
             *
             * If a ControllerException is caught here we handle any
             * nested `\Next\Controller\ControllerException` existing
             * within the process
             *
             * This allows, for example, an Standardized Error that
             * occurs in a specific action of dispatched Application
             * be virtually redirected to the previous action page
             * without need to repeat all the code related to this page
             *
             * Note that this won't do any magic and handle a
             * `ControllerException` thrown in a method that's being
             * accessed through a previously handled `ControllerException`
             *
             * This WILL end up being a infinite recursion
             */
            return $this -> handleExceptionCallback( $application, $e );

        } catch( ViewException $e ) {

            /**
             * @internal
             *
             * Same rules for when a ViewException is triggered during
             * the dispatching process, except here a ViewException
             * is thrown while handling any ControllerException thrown
             */
            if( ob_get_length() ) {

                // We want ONLY the Exception Template

                ob_end_clean();
            }

            ExceptionHandler::development( $e );
        }
    }
}