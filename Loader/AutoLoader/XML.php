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

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AutoLoadable.php';    # AutoLoadable Interface

use Next\Loader\AutoLoader\AutoLoadable;    # AutoLoadable Interface
use Next\Loader\LoaderException;            # AutoLoader Exceptions Class

/**
 * XML Class Map File AutoLoader
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XML implements AutoLoadable {

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
     * @throws \Next\LoaderException
     *  XML Filename was not set
     *
     * @throws \Next\LoaderException
     *  XML File is not readable
     */
    public function __construct( $file ) {

        // XML File must exist

        if( ! is_file( $file ) ) {

            throw LoaderException::unfullfilledRequirements(

                'XML File File <strong>%s</strong> doesn\'t exists',

                array( $file )
            );
        }

        // ... and must be readable

        if( ! is_readable( $file ) ) {

            throw LoaderException::unfullfilledRequirements(

                'XML File <strong>%s</strong> is not readable',

                LoaderException::UNFULFILLED_REQUIREMENTS,

                $file
            );
        }

        self::$map = file_get_contents( $file );
    }

    // Interface Method Implementation

    /**
     * AutoLoading Function
     *
     * @return Closure
     *  An anonymous function to be invoked as SPL Autoload callback
     */
    public function call() {

        $iterator = new \SimpleXMLElement( self::$map );

        return function( $classname ) use( $iterator ) {

            $xpath = $iterator -> xpath(

                sprintf( "//class[@name='%s']", $classname )
            );

            if( $xpath === FALSE || count( $xpath ) <= 0 ) {
                throw LoaderException::notFound( $classname );
            }

            $attributes = $xpath[0] -> attributes();

            include (string) $attributes['path'];
        };
    }
}
