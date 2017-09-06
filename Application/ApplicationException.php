<?php

/**
 * Application Exception Class | Application\ApplicationException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Application;

use Next\Components\Object;    # Object Class

/**
 * Defines wrapper static methods for all Exceptions thrown
 * within the Application Module
 *
 * @package    Next\Application
 */
class ApplicationException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000033, 0x00000065 );

    /**
     * Invalid Application.
     *
     * <p>Used by \Next\Application\Chain</p>
     *
     * @var integer
     */
    const INVALID_APPLICATION        = 0x00000033;

    /**
     * Application has no View Engine assigned
     *
     * @var integer
     */
    const INVALID_ROUTER             = 0x00000034;

    /**
     * Application has an invalid View Engine
     *
     * @var integer
     */
    const INVALID_VIEW_ENGINE        = 0x00000035;

    /**
     * Application has an invalid Session Manager
     *
     * @var integer
     */
    const INVALID_SESSION_MANAGER    = 0x00000036;

    // Exceptions Messages

    /**
     * Invalid Application
     *
     * Given Object is not a valid Application because it doesn't
     * implements \Next\Application\\Application
     *
     * @param \Next\Components\Object $object
     *  Object assigned as Application
     *
     * @return \Next\Application\ApplicationException
     *  Exception for invalid Applications
     */
    public static function invalidApplication( Object $object ) {

        return new self(

            '<strong>%s</strong> is not a valid Application.

            <br /><br />

            Applications must implement Application Interface (Next\Application\Application)',

            self::INVALID_APPLICATION,

            (string) $object
        );
    }

    /**
     * Invalid Router
     *
     * Assigned Router is not valid because it doesn't
     * implements \Next\Controller\Router\Router
     *
     * @return \Next\Application\ApplicationException
     *  Exception for Applications with an invalid Router
     */
    public static function invalidRouter() {

        return new self(

            'Routers must implement Router Interface (Next\Controller\Router\Router)',

            self::INVALID_ROUTER
        );
    }

    /**
     * Invalid View Engine
     *
     * Assigned View Engine is not valid because it doesn't
     * implements \Next\View\View
     *
     * @return \Next\Application\ApplicationException
     *  Exception for Applications with invalid View Engine
     */
    public static function invalidViewEngine() {

        return new self(

            'View Engines must implement View Interface (Next\View\View)',

            self::INVALID_VIEW_ENGINE
        );
    }

    /**
     * Invalid Session Manager
     *
     * Assigned Session Manager is not valid because, currently, only
     * Objects instance of \Next\Session\Manager are accepted
     *
     * @return \Next\Application\ApplicationException
     *  Exception for Applications with invalid Session Manager
     */
    public static function invalidSessionManager() {

        return new self(

            'Session Manager must be an instance of \Next\Session\Manager',

            self::INVALID_SESSION_MANAGER
        );
    }
}