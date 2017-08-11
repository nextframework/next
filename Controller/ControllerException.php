<?php

/**
 * Controllers Exception Class | Controller\ControllerException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller;

use Next\HTTP\Request\RequestException;    # HTTP Request Exception
use Next\View\ViewException;               # View Exception
use Next\Components\Object;                # Object Class

/**
 * Defines wrapper static methods for all Exceptions thrown
 * within the Controller Module
 *
 * The ControllerException Class is also part of Error Standardization Concept
 * through which all errors that must be sent to UI from the
 * \Next\Controller\Controller is automatically done by
 * \Next\Controller\Dispatcher\Dispatcher in charge, requiring the
 * developer only to echo a Template Variable
 *
 * @package    Next\Controller
 */
class ControllerException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000198, 0x000001CA );

    /**
     * Invalid Controller
     *
     * <p>Used by \Next\Controller\Chain</p>
     *
     * @var integer
     */
    const INVALID_CONTROLLER                      = 0x00000198;

    /**
     * Unnecessary tests being made
     *
     * @var integer
     */
    const UNNECESSARY_TEST                        = 0x00000199;

    /**
     * HTTP Param not Found
     *
     * @var integer
     */
    const PARAM_NOT_FOUND                         = 0x0000019A;

    /**
     * Template Variable Assignment failure
     *
     * @var integer
     */
    const TEMPLATE_VARIABLE_ASSIGNMENT_FAILURE    = 0x0000019B;

    /**
     * Template Variable Removal failure
     *
     * @var integer
     */
    const TEMPLATE_VARIABLE_REMOVAL_FAILURE       = 0x0000019C;

    // Exception Messages

    /**
     * Invalid Controller
     *
     * Given Object is not a valid Controller because it doesn't
     * implements \Next\Controller\Controller Interface
     *
     * @param \Next\Components\Object $object
     *  Object used as Controller
     *
     * @return \Next\Controller\ControllerException
     *  Exception for Invalid Controllers
     */
    public static function invalidController( Object $object ) {

        return new self(

            '<strong>%s</strong> is not a valid Controller.

            <br /><br />

            Controllers must implement Controller Interface',

            self::INVALID_CONTROLLER,

            (string) $object
        );
    }

    /**
     * Unnecessary Tests
     *
     * Enforces the concept of properties started with underscore
     * to be considered as private even when their real visibility are
     * protected
     *
     * And "private" properties should not have their existence tested,
     * because they are always in there.
     *
     * @return \Next\Controller\ControllerException
     *  Exception for unnecessary tests being made
     */
    public static function unnecessaryTest() {

        return new self(

            'You shouldn\'t try to test if a internal property exists.

            <br /><br />

            If you\'re prefixing your GET parameters with an underscore, you are doing it wrong',

            self::UNNECESSARY_TEST
        );
    }

    /**
     * HTTP GET Param not found
     *
     * @param \Next\HTTP\Request\RequestException $e
     *     RequestException caught
     *
     * @return \Next\Controller\ControllerException
     *  Exception for missing HTTP GET Param
     */
    public static function paramNotFound( RequestException $e ) {

        return new self(

            $e -> getMessage(),

            self::PARAM_NOT_FOUND
        );
    }

    /**
     * Template Variable Assignment Failure
     *
     * @param \Next\View\ViewException $e
     *     ViewException caught
     *
     * @return \Next\Controller\ControllerException
     *  Exception for Template Variable assignment failure
     */
    public static function assignmentFailure( ViewException $e ) {

        return new self(

            $e -> getMessage(),

            self::TEMPLATE_VARIABLE_ASSIGNMENT_FAILURE
        );
    }

    /**
     * Template Variable Removal Failure
     *
     * @param \Next\View\ViewException $e
     *     ViewException caught
     *
     * @return \Next\Controller\ControllerException
     *  Exception for Template Variable removal failure
     */
    public static function removalFailure( ViewException $e ) {

        return new self(

            $e -> getMessage(),

            self::TEMPLATE_VARIABLE_REMOVAL_FAILURE
        );
    }
}