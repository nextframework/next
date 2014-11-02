<?php

namespace Next\Loader\AutoLoader;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AutoLoadable.php';        # AutoLoadable Interface

use Next\Loader\AutoLoader\AutoLoadable;    # AutoLoadable Interface

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

            $classname = stream_resolve_include_path(

                str_replace( '\\', DIRECTORY_SEPARATOR, $classname ) . '.php'
            );

            if( $classname !== FALSE ) {

                include $classname;
            }
        };
    }
}
