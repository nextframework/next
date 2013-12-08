<?php

namespace Next\Components;

use Next\Components\Interfaces\Contextualizable;    # Contextualizable Interface
use Next\Components\Invoker;                        # Invoker Class
use Next\Components\Utils\ArrayUtils;               # Array Utils Class

/**
 * Context Class
 *
 * Context registers Invoker objects to allow Invoker Caller's context to be
 * extended, almost like PHP 5.4 Traits
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Context implements Contextualizable {

    /**
     * Context Callables
     *
     * @var array $callables
     */
    private $callables = array();

    /**
     * Register a new Invoker Object to be used as context extension
     *
     * @param Next\Components\Invoker $invoker
     *   Invoker Object
     *
     * @param string|array|optional $methods
     *   One or more methods accessible through extended Context.
     *
     *   If NULL (default) all Object methods (respecting the filtering
     *   conditions) will be accessible
     *
     * @return Next\Components\Context
     *   Context Instance (Fluent Interface)
     */
    function extend( Invoker $invoker, $methods = NULL ) {

        $caller = $invoker -> getCaller() -> getClass() -> getName();
        $callee = $invoker -> getCallee();

        // Granting access to a specific set of methods...

        if( ! is_null( $methods ) ) {

            $this -> callables[ $caller ][] = array( &$callee, $methods );

            return $this;
        }

        // Grant access to (almost) them all

        /**
         * @internal
         * Filtering acceptable methods to public, non-magic and
         * not belonged to Next\Components\Object or Next\Components\Context
         */
        $methods = array_filter(

            $callee -> getClass()
                    -> getMethods( \ReflectionMethod::IS_PUBLIC ),

            function( $method ) {

                return (

                    ( $method -> class !== 'Next\Components\Object' ) &&

                    ( $method -> class !== 'Next\Components\Context' ) &&

                    ( substr( $method -> name, 0, 2 ) !== '__' )
                );
            }
        );

        // Building Context Structure

        $this -> callables[ $caller ][] = array(

            &$callee,

            // We want only the names

            array_map(

                function( $method ) {

                    return $method -> name;
                },

                $methods
            )
        );

        return $this;
    }

    /**
     * Invoke an extended resource from a caller context
     *
     * @param Next\Components\Object $caller
     *   Caller Object
     *
     * @param string $method
     *   Callable resource name
     *
     * @param array $args
     *   Calling Arguments
     *
     * @return mixed|boolean
     *   Return what extended method returns.
     *
     *   If invoking process fail, false will returned.
     *
     * @throws Next\Components\Debug\Exception
     *   Called resource is not known as an extended method
     */
    public function call( Object $caller, $method, array $args = array() ) {

        $caller = $caller -> getClass() -> getName();

        if( array_key_exists( $caller, $this -> callables ) ) {

            $offset = ArrayUtils::search(

                $this -> callables[ $caller ], $method
            );

            if( $offset != -1 ) {

                try {

                    $reflector = new \ReflectionMethod(

                        $this -> callables[ $caller ][ $offset ][ 0 ], $method
                    );

                    return $reflector -> invokeArgs(

                        $this -> callables[ $caller ][ $offset ][ 0 ], $args
                    );

                } catch( \ReflectionException $e ) {

                    return FALSE;
                }
            }
        }

        // Unknown Method

        throw \Next\Components\Debug\Exception::wrongUse(

            'Method <strong>%s</strong> could not be matched against any
            methods in extended Context',

            array( $method )
        );
    }

    /**
     * Get Context Callables
     *
     * @return array
     *   Registered Context Callables
     */
    public function getCallables() {
        return $this -> callables;
    }
}