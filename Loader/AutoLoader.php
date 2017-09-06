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

use Next\Loader\LoaderException;                                  # Loader Exception Class
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
    private $autoloaders;

    /**
     * AutoLoader Constructor
     */
    public function __construct() {

        // Setting Up AutoLoaders Object Storage

        $this -> autoloaders = new \SplObjectStorage;
    }

    /**
     * Register a new AutoLoader Object
     *
     * @param \Next\Loader\AutoLoader\AutoLoader $autoloader
     *  The AutoLoader Object to be registered
     *
     * @return \Next\AutoLoader
     *  AutoLoader Instance (Fluent Interface)
     *
     * @throws \Next\LoaderException
     *  Trying to register an already registered AutoLoader Object
     */
    public function registerAutoLoader( AutoLoaderInterface $autoloader ) {

        if( $this -> autoloaders -> contains( $autoloader ) ) {

            require_once 'LoaderException.php';

            throw LoaderException::duplicated();
        }

        $this -> autoloaders -> attach( $autoloader );

        spl_autoload_register( $autoloader -> call() );

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
     *
     * @throws \Next\LoaderException
     *  Trying to unregister a non registered AutoLoader Object
     */
    public function unregisterAutoLoader( AutoLoaderInterface $autoloader ) {

        if( ! $this -> autoloaders -> contains( $autoloader ) ) {

            require_once 'LoaderException.php';

            throw LoaderException::unknown();
        }

        $this -> autoloaders -> detach( $autoloader );

        spl_autoload_unregister( $autoloader -> call() );

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
