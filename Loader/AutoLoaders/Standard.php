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

/**
 * Exception Class(es)
 */
require_once __DIR__ . '/../../Exception/Exceptions/InvalidArgumentException.php';

use Next\Exception\Exceptions\InvalidArgumentException;

require_once __DIR__ . '/AutoLoader.php';    # AutoLoader Interface

use Next\Loader\AutoLoaders\AutoLoader;      # AutoLoader Interface

/**
 * PHP-Array Class Map File AutoLoader
 *
 * @package    Next\Loader
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Loader\AutoLoaders\AutoLoader
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
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if PHP-Array Filename was not set
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if PHP-Array File is not readable
     */
    public function __construct( $file ) {

        // PHP-Array File must exist and be readable

        if( ! is_file( $file ) ) {

            throw new InvalidArgumentException(
                sprintf( 'PHP-Array File <strong>%s</strong> doesn\'t exists', $file )
            );
        }

        if( ! is_readable( $file ) ) {

            throw new InvalidArgumentException(
                sprintf( 'PHP-Array File <strong>%s</strong> is not readable', $file )
            );
        }

        self::$map = include_once $file;
    }

    // AutoLoader Interface Method Implementation

    /**
     * AutoLoading Function
     *
     * @return callable
     *  An anonymous function to be invoked as SPL Autoload callback
     */
    public function call() : callable {

        $map = self::$map;

        return function( $classname ) use( $map ) : bool {

            if( array_key_exists( $classname, $map ) ) {

                include $map[ $classname ];

                return TRUE;
            }

            return FALSE;
        };
    }
}
