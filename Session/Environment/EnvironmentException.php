<?php

namespace Next\Session\Environment;

/**
 * Session Environment Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class EnvironmentException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x000006C6, 0x000006F8 );

    /**
     * Invalid Environment
     *
     * @var integer
     */
    const INVALID_ENVIRONMENT    =    0x000006C6;

    /**
     * Locked Environment
     *
     * @var integer
     */
    const LOCKED                 =    0x000006C7;

    /**
     * Destroyed Environment
     *
     * @var integer
     */
    const DESTROYED              =    0x000006C8;

    /**
     * Undefined Index
     *
     * @var integer
     */
    const UNDEFINED_INDEX        =    0x000006C9;

    // Exception Messages

    /**
     * Invalid Environment
     *
     * @return Next\Session\Environment\EnvironmentException
     *   Exception for invalid Environment name
     */
    public static function invalidEnvironment() {

        return new self(

            'Session environment must not start with a number',

            self::INVALID_ENVIRONMENT
        );
    }

    /**
     * Environment is Locked
     *
     * @param string $environmentName
     *   Environment Name
     *
     * @return Next\Session\Environment\EnvironmentException
     *   Exception for locked Environment being used
     */
    public static function locked( $environmentName ) {

        return new self(

            'Environment <strong>%s</strong> is currently locked for changes',

            self::LOCKED,

            $environmentName
        );
    }

    /**
     * Environment was explicitly Destroyed
     *
     * @param string $environmentName
     *   Environment Name
     *
     * @return Next\Session\Environment\EnvironmentException
     *   Exception for explicitly destroyed Environment being used
     */
    public static function destroyed( $environmentName ) {

        return new self(

            'Environment <strong>%s</strong> has been explicitly destroyed',

            self::DESTROYED,

            $environmentName
        );
    }

    /**
     * Undefined Index
     *
     * @param string $index
     *   Desired Index
     *
     * @param string $environmentName
     *   Environment Name
     *
     * @return Next\Session\Environment\EnvironmentException
     *   Exception for undefined index being used
     */
    public static function undefinedIndex( $index, $environmentName ) {

        return new self(

            'Undefined index <strong>%s</strong> in Environment <strong>%s</strong>',

            self::UNDEFINED_INDEX,

            array( $index, $environmentName )
        );
    }
}
