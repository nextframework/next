<?php

/**
 * Autoloader Class | Loader\AutoLoader.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Loader;

use Next\Loader\AutoLoaders\AutoLoader as AutoLoaderInterface;    # AutoLoadader Interface

/**
 * AutoLoader Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class AutoLoader {

    /**
     * AutoLoaders Storage
     *
     * @var SplObjectStorage $autoloaders
     */
    private $autoloaders = [];

    /**
     * Register a new AutoLoader Object
     *
     * @param \Next\Loader\AutoLoader\AutoLoader $autoloader
     *  The AutoLoader Object to be registered
     *
     * @return \Next\AutoLoader
     *  AutoLoader Instance (Fluent Interface)
     */
    public function registerAutoLoader( AutoLoaderInterface $autoloader ) {

        $classname = get_class( $autoloader );

        if( ! array_key_exists( $classname, $this -> autoloaders ) ) {

            $this -> autoloaders[ get_class( $autoloader) ] = $autoloader;

            spl_autoload_register( $autoloader -> call() );
        }

        return $this;
    }

    /**
     * Unregister an AutoLoader Object
     *
     * @param \Next\Loader\AutoLoader\AutoLoader $autoloader
     *  The AutoLoader Object to be registered
     *
     * @return \Next\AutoLoader
     *  AutoLoader Instance (Fluent Interface)
     */
    public function unregisterAutoLoader( AutoLoaderInterface $autoloader ) {

        $classname = get_class( $autoloader );

        if( array_key_exists( $classname, $this -> autoloaders ) ) {

            spl_autoload_unregister( $autoloader -> call() );

            unset( $this -> autoloaders[ $classname ] );
        }

        return $this;
    }

    /**
     * Get registered AutoLoaders
     *
     * @return SplObjectStorage
     */
    public function getAutoLoaders() {
        return $this -> autoloaders;
    }
}
