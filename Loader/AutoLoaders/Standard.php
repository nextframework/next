<?php

/**
 * Standard AutoLoader Class | Loader\AutoLoader\Standard.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Loader\AutoLoader;

require_once __DIR__ . '/AutoLoader.php';    # AutoLoader Interface

use Next\Loader\AutoLoaders\AutoLoader;      # AutoLoader Interface
use Next\Loader\LoaderException;             # AutoLoader Exceptions Class

/**
 * PHP-Array Class Map File AutoLoader
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Standard implements AutoLoader {

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
     * @throws \Next\LoaderException
     *  PHP-Array Filename was not set
     *
     * @throws \Next\LoaderException
     *  PHP-Array File is not readable
     */
    public function __construct( $file ) {

        // PHP-Array File must exist

        if( ! is_file( $file ) ) {

            throw LoaderException::unfullfilledRequirements(

                'PHP-Array File <strong>%s</strong> doesn\'t exists',

                [ $file ]
            );
        }

        // ... and must be readable

        if( ! is_readable( $file ) ) {

            throw LoaderException::unfullfilledRequirements(

                'PHP-Array File <strong>%s</strong> is not readable',

                [ $file ]
            );
        }

        self::$map = include_once $file;
    }

    // AutoLoader Interface Method Implementation

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

                return TRUE;
            }
        };
    }
}
