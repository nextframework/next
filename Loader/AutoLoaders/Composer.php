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

/**
 * Exception Class(es)
 */
require_once __DIR__ . '/../../Exception/Exceptions/RuntimeException.php';
require_once __DIR__ . '/../../Exception/Exceptions/InvalidArgumentException.php';

use Next\Exception\Exceptions\RuntimeException;
use Next\Exception\Exceptions\InvalidArgumentException;

require_once __DIR__ . '/AutoLoader.php';    # AutoLoader Interface

use Next\Loader\AutoLoaders\AutoLoader;      # AutoLoader Interface

/**
 * The Composer AutoLoader maps the dynamically generated Composer AutoLoader
 * to make it compatible to Next Framework
 *
 * @package    Next\Loader
 *
 * @uses       Next\Exception\Exceptions\RuntimeException
 *             Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Loader\AutoLoaders\AutoLoader
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
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if PHP Filepath informed of Composer AutoLoader
     *  wasn't defined
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if PHP Filepath informed of Composer AutoLoader
     *  doesn't resolve to a valid file
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if PHP file informed doesn't seem to be a valid
     *  Composer AutoLoader
     */
    public function __construct( array $options = [] ) {

        if( ! array_key_exists( 'path', $options ) ) {

            throw new InvalidArgumentException(
                'Missing required configuration option <strong>path</strong>'
            );
        }

        if( stream_resolve_include_path( $options['path'] ) === FALSE ) {

            throw new InvalidArgumentException(
                'Composer AutoLoader File provided doesn\'t exist'
            );
        }

        $autoLoader = file_get_contents( $options['path'] );

        // Finding auto-generated Composer AutoLoader Class

        if( preg_match( '/return (Composer.*?)::getLoader\(\)/', $autoLoader, $autoLoaderClass ) == 0 ) {

            throw new RuntimeException(

                'Unable to find an auto-generated Composer AutoLoader Class
                in the file provided'
            );
        }

        // Including Composer AutoLoader

        require sprintf(
            self::COMPOSER_AUTOLOADER_FILE, dirname( $options['path'] )
        );

        $this -> autoLoaderClass = $autoLoaderClass[ 1 ];
    }

    /**
     * AutoLoading Function
     *
     * @return callable
     *  An anonymous function to be invoked as SPL Autoloader callback
     */
    public function call() : callable {

        return function( $classname ) :? bool {

            $loader = call_user_func( [ $this -> autoLoaderClass, 'getLoader' ] );

            return $loader -> loadClass( $classname );
        };
    }
}
