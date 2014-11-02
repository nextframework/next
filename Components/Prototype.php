<?php

namespace Next\Components;

use Next\Components\Interfaces\Prototypical;          # Prototypical Interface
use Next\Components\Utils\ArrayUtils;                 # ArrayUtils Class

/**
 * Prototype Class
 *
 * Defines the base of a Prototypical Object allowing all of its
 * instances have callable features available
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class Prototype implements Prototypical  {

    /**
     * Registered Prototypes
     *
     * @var array $prototypes
     */
    private static $prototypes = array();

    /**
     * Implement a new callable resource, prototyping it to Object
     *
     * @param string $name
     *  Callable resource name. Should be unique
     *
     * @param callable $callable
     *  Callable resource
     *
     * @param array $args
     *  Default arguments available to callable resource
     *
     * @return Next\Components\Prototype
     *  Prototype Instance (Fluent Interface)
     */
    public function implement( $name, $callable, $args = array() ) {

        self::$prototypes[ (string) $name ] = array( $callable, (array) $args );

        return $this;
    }

    /**
     * Invoke a prototyped resource from a caller context
     *
     * @param Next\Components\Object $caller
     *  Caller Object
     *
     * @param string $method
     *  Callable resource name
     *
     * @param array $args
     *  Calling Arguments
     *
     * @return Next\Components\Object
     *  Caller Object updated
     *
     * @throws Next\Components\Debug\Exception
     *  Called resource is not known as a prototype nor as a extended method
     */
    public function call( Object $caller, $method, array $args = array() ) {

        if( isset( self::$prototypes[ $method ] ) ) {

            // Merging always optional arguments with called arguments

            if( count( $args ) > 0 ) {

                ArrayUtils::insert( self::$prototypes[ $method ][ 1 ], $args );

            } else {

                // Nothing to Merge? OK!

                $args =& self::$prototypes[ $method ][ 1 ];
            }

            $result = call_user_func_array(

                self::$prototypes[ $method ][ 0 ], $args
            );

            /**
             * @internal
             *
             * If operation results in an Object, let's return it
             *
             * This ensures operations of one type can return a different type
             */
            if( $result instanceof Object ) {
                return $result;
            }

            // Otherwise let's update caller Object

            return $caller -> set( $result );
        }

        throw \Next\Components\Debug\Exception::wrongUse(

            'Method <strong>%s</strong> could not be matched against any
            methods in extended Context or prototyped functions',

            array( $method )
        );
    }

    /**
     * Get Prototyped Resources
     *
     *  @return array
     *    All prototyped resources
     */
    public function getPrototypes() {
        return self::$prototypes;
    }
}