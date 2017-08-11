<?php

/**
 * HTTP Stream AutoLoader Class | Loader\AutoLoader\Stream.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Loader\AutoLoader;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AutoLoadable.php';        # AutoLoadable Interface

use Next\Loader\AutoLoader\AutoLoadable;    # AutoLoadable Interface
use Next\Loader\LoaderException;            # AutoLoader Exceptions Class

/**
 * Stream AutoLoader
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Stream implements AutoLoadable {

    // Interface Method Implementation

    /**
     * AutoLoading Function
     *
     * @return Closure
     *  An anonymous function to be invoked as SPL Autoload callback
     */
    public function call() {

        return function( $classname ) {

            $resolvedClassname = stream_resolve_include_path(

                str_replace( '\\', DIRECTORY_SEPARATOR, $classname ) . '.php'
            );

            if( $resolvedClassname === FALSE ) {
                throw LoaderException::notFound( $classname );
            }

            include $resolvedClassname;
        };
    }
}
