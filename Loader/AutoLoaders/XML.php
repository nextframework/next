<?php

/**
 * XML AutoLoader Class | Loader\AutoLoader\XML.php
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
 * XML Class Map File AutoLoader
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XML implements AutoLoader {

    /**
     * Map Data
     *
     * @var array $map
     */
    private static $map;

    /**
     * XML Map File AutoLoader
     *
     * @param string $file
     *  XML Map File to work with
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if XML Filepath informed is not a file
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if XML File is not readable
     */
    public function __construct( $file ) {

        // XML File must exist

        if( ! is_file( $file ) ) {

            throw new InvalidArgumentException(
                spritnf( 'XML File File <strong>%s</strong> doesn\'t exists', $file )
            );
        }

        // ... and must be readable

        if( ! is_readable( $file ) ) {

            throw new InvalidArgumentException(
                sprintf( 'XML File <strong>%s</strong> is not readable', $file )
            );
        }

        self::$map = file_get_contents( $file );
    }

    // AutoLoader Interface Method Implementation

    /**
     * AutoLoading Function
     *
     * @return Closure
     *  An anonymous function to be invoked as SPL Autoloader callback
     */
    public function call() {

        $iterator = new \SimpleXMLElement( self::$map );

        return function( $classname ) use( $iterator ) {

            $xpath = $iterator -> xpath(

                sprintf( "//class[@name='%s']", $classname )
            );

            if( $xpath !== FALSE && count( $xpath ) > 0 ) {

                $attributes = $xpath[0] -> attributes();

                include (string) $attributes['path'];

                return TRUE;
            }
        };
    }
}
