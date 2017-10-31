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

/**
 * Exception Class(es)
 */
require_once __DIR__ . '/../Exception/Exceptions/LogicException.php';
require_once __DIR__ . '/../Exception/Exceptions/UnderflowException.php';
require_once __DIR__ . '/../Exception/Exceptions/InvalidArgumentException.php';

use Next\Exception\Exceptions\LogicException;
use Next\Exception\Exceptions\UnderflowException;
use Next\Exception\Exceptions\InvalidArgumentException;

require_once __DIR__ . '/AutoLoaders/AutoLoader.php';

use Next\Loader\AutoLoaders\AutoLoader as AutoLoaderInterface;    # AutoLoadader Interface

/**
 * The AutoLoader Class is responsible to register and unregister
 * AutoLoader Strategies
 *
 * @package    Next\Loader
 *
 * @uses       Next\Exception\Exceptions\LogicException
 *             Next\Exception\Exceptions\UnderflowException
 *             Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Loader\AutoLoaders\AutoLoader
 */
class AutoLoader {

    /**
     * AutoLoaders Storage
     *
     * @var array $autoloaders
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
     *
     * @throws \Next\Exception\Exceptions\LogicException
     *  Thrown if trying to register an already registered AutoLoader
     */
    public function registerAutoLoader( AutoLoaderInterface $autoloader ) : AutoLoader {

        $classname = get_class( $autoloader );

        if( array_key_exists( $classname, $this -> autoloaders ) ) {

            throw new LogicArgumentException(

                sprintf(

                    'AutoLoader <strong>%s</strong> is already registered',

                    $classname
                )
            );
        }

        $this -> autoloaders[ $classname ] = $autoloader -> call();

        spl_autoload_register( $this -> autoloaders[ $classname ] );

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
     * @throws \Next\Exception\Exceptions\UnderflowException
     *  Thrown if trying to remove an AutoLoader Strategy Object when none
     *  have been registered
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if the AutoLoader trying to be unregistered can't be found
     */
    public function unregisterAutoLoader( AutoLoaderInterface $autoloader ) : AutoLoader {

        $classname = get_class( $autoloader );

        if( count( $this -> autoloaders ) == 0 ) {
            throw new UnderflowException( 'There are no  AutoLoaders to remove' );
        }

        if( ! array_key_exists( $classname, $this -> autoloaders ) ) {

            throw new InvalidArgumentException(

                sprintf(

                    'AutoLoader <strong>%s</strong> could not be found',

                    $classname
                )
            );
        }

        spl_autoload_unregister( $this -> autoloaders[ $classname ] );

        unset( $this -> autoloaders[ $classname ] );

        return $this;
    }

    /**
     * Get registered AutoLoaders
     *
     * @return array
     *  Registered AutoLoaders
     */
    public function getAutoLoaders() : array {
        return $this -> autoloaders;
    }
}
