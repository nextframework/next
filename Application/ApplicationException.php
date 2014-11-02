<?php

namespace Next\Application;

use Next\Components\Object;    # Object Class

/**
 * Application Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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
     * <p>Used by Next\Application\Chain</p>
     *
     * @var integer
     */
    const INVALID_APPLICATION = 0x00000033;

    /**
     * Application has no View Engine assigned
     *
     * @var integer
     */
    const NO_VIEW_ENGINE = 0x00000034;

    /**
     * Application has an invalid View Engine
     *
     * @var integer
     */
    const INVALID_VIEW_ENGINE = 0x00000035;

    // Exceptions Messages

    /**
     * Invalid Application
     *
     * Given Object is not a valid Application because it doesn't
     * implements Next\Application\\Application
     *
     * @param Next\Components\Object $object
     *  Object assigned as Application
     *
     * @return Next\Application\ApplicationException
     *  Exception for invalid Applications
     */
    public static function invalidApplication( Object $object ) {

        return new self(

            '<strong>%s</strong> is not a valid Application.

            <br /><br />

            Applications must implement Application Interface',

            self::INVALID_APPLICATION,

            (string) $object
        );
    }

    /**
     * Missing View Engine
     *
     * Application has no View Engine assigned to it
     *
     * @param Next\Application\Application $application
     *  Assigned Application
     *
     * @return Next\Application\ApplicationException
     *  Exception for Applications without View Engine
     */
    public static function noViewEngine( Application $application ) {

        return new self(

            'Application <strong>%s</strong> has no View Engine assigned',

            self::NO_VIEW_ENGINE,

            (string) $application
        );
    }

    /**
     * Invalid View Engine
     *
     * Assigned View Engine is not valid because it doesn't
     * implements Next\View\View
     *
     * @return Next\Application\ApplicationException
     *  Exception for Applications with invalid View Engine
     */
    public static function invalidViewEngine() {

        return new self(

            'View Engines must implement View Interface',

            self::INVALID_VIEW_ENGINE
        );
    }
}