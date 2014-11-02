<?php

namespace Next\Loader\AutoLoader;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AutoLoadable.php';        # AutoLoadable Interface

use Next\Loader\LoaderException;            # Loader Exception Class
use Next\Loader\AutoLoader\AutoLoadable;    # AutoLoadable Interface

/**
 * PHP-Array Class Map File AutoLoader
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Standard implements AutoLoadable {

    /**
     * Map Data
     *
     * @var array $map
     */
    private static $map;

    /**
     * PHP-Array Map File AutoLoader
     *
     * @param string $file
     *  PHP-Array Map File to work with
     *
     * @throws Next\LoaderException
     *  PHP-Array Filename was not set
     *
     * @throws Next\LoaderException
     *  PHP-Array File is not readable
     */
    public function __construct( $file ) {

        // PHP-Array File must exist

        if( ! is_file( $file ) ) {

            require_once '/../LoaderException.php';

            throw LoaderException::unfullfilledRequirements(

                'PHP-Array File <strong>%s</strong> doesn\'t exists',

                array( $file )
            );
        }

        // ... and must be readable

        if( ! is_readable( $file ) ) {

            require_once __DIR__ . '/LoaderException.php';

            throw LoaderException::unfullfilledRequirements(

                'PHP-Array File <strong>%s</strong> is not readable',

                LoaderException::UNFULFILLED_REQUIREMENTS,

                $file
            );
        }

        self::$map = include_once $file;
    }

    // Interface Method Implementation

    /**
     * AutoLoading Function
     *
     * @return Closure
     *  An anonymous function to be invoked as SPL Autoload callback
     */
    public function call() {

        $map =& self::$map;

        return function( $classname ) use( $map ) {

            if( array_key_exists( $classname, $map ) ) {

                include $map[ $classname ];
            }
        };
    }
}
