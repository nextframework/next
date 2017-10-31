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

/**
 * Stream AutoLoader
 *
 * @package    Next\Loader
 *
 * @uses       Next\Loader\AutoLoaders\AutoLoader
 */
class Stream implements AutoLoader {

    // AutoLoader Interface Method Implementation

    /**
     * AutoLoading Function
     *
     * @return Closure
     *  An anonymous function to be invoked as SPL Autoloader callback
     */
    public function call() : callable {

        return function( $classname ) : bool {

            $resolvedClassname = stream_resolve_include_path(
                strtr( $classname, [ '\\' => DIRECTORY_SEPARATOR ] ) . '.php'
            );

            if( $resolvedClassname !== FALSE ) {

                include $resolvedClassname;

                return TRUE;
            }

            return FALSE;
        };
    }
}
