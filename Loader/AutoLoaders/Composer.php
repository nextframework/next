<?php

/**
 * HTTP Stream AutoLoader Class | Loader\AutoLoader\Stream.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Loader\AutoLoaders;

require_once __DIR__ . '/AutoLoader.php';    # AutoLoader Interface

use Next\Loader\AutoLoaders\AutoLoader;      # AutoLoader Interface
use Next\Loader\LoaderException;             # AutoLoader Exceptions Class

/**
 * Stream AutoLoader
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Composer implements AutoLoader {

    /**
     * Composer real AutoLoader relative to given path
     *
     * @var string
     */
    const COMPOSER_AUTOLOADER_FILE = '%s/composer/autoload_real.php';

    /**
     * Composer auto-generated Classname
     *
     * @var string $autoLoaderClass
     *  Composer AutoLoader Classname
     */
    protected $autoLoaderClass;

    // AutoLoader Interface Method Implementation

    /**
     * Composer AutoLoader constructor.
     * Checks AutoLoader Integrity, finds the Composer auto-generated
     * AutoLoader Classname and includes its real AutoloAder file
     *
     * @param array|optional $options
     *  AutoLoader Options
     */
    public function __construct( array $options = [] ) {

        if( ! array_key_exists( 'path', $options ) ) {

            throw LoaderException::unfullfilledRequirements(
                'Missing required configuration option \'path\''
            );
        }

        if( stream_resolve_include_path( $options['path'] ) === FALSE ) {

            throw LoaderException::unfullfilledRequirements(
                'Composer AutoLoader File provided doesn\'t exist'
            );
        }

        $autoLoader = file_get_contents( $options['path'] );

        // Finding auto-generated Composer AutoLoader Class

        preg_match( '/return (Composer.*?)::getLoader\(\)/', $autoLoader, $autoLoaderClass );

        if( $autoLoaderClass == 0 ) {

            throw LoaderException::unfullfilledRequirements(
                'Unable to find an auto-generated Composer AutoLoader Class in the file provided'
            );
        }

        // Including Composer AutoLoader

        require sprintf( self::COMPOSER_AUTOLOADER_FILE, dirname( $options['path'] ) );

        $this -> autoLoaderClass = $autoLoaderClass[ 1 ];
    }

    /**
     * AutoLoading Function
     *
     * @return Closure
     *  An anonymous function to be invoked as SPL Autoloader callback
     */
    public function call() {

        return function( $classname ) {

            $loader = call_user_func( [ $this -> autoLoaderClass, 'getLoader' ] );

            return $loader -> loadClass( $classname );
        };
    }
}
