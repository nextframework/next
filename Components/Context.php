<?php

/**
 * Extended Context Component Class | Components\Context.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components;

use Next\Components\Interfaces\Contextualizable;    # Contextualizable Interface
use Next\Components\Invoker;                        # Invoker Class
use Next\Components\Utils\ArrayUtils;               # Array Utils Class

/**
 * Bridges two \Next\Components\Object instances wrapped by a
 * \Next\Components\Invoker Object and handles methods calling
 * and properties setting/getting within the Extended Context
 *
 * @package    Next\Components
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
     * @param \Next\Components\Invoker $invoker
     *  Invoker Object
     *
     * @param string|array|optional $methods
     *  One or more methods accessible through extended Context.
     *  Defaults to NULL, which means almost all PUBLIC methods will be accessible
     *
     * @param string|array|optional $properties
     *  One or more properties accessible through extended Context
     *  Defaults to NULL, which means all PROTECTED properties will be accessible
     *
     * @return \Next\Components\Context
     *  Context Instance (Fluent Interface)
     */
    function extend( Invoker $invoker, $methods = NULL, $properties = NULL ) {

        $caller = $invoker -> getCaller();
        $callee = $invoker -> getCallee();

        if( $callee instanceof Mimicker ) {

            $reflector = new \ReflectionObject( $callee -> getMimicker() );

        } else {

            $reflector = $callee -> getClass();
        }

        // Methods

        if( is_null( $methods ) ) {
            $methods = $reflector -> getMethods( \ReflectionMethod::IS_PUBLIC );
        }

        // Restricting access to methods of some classes to avoid infinite loops

        $methods = array_filter( $methods, array( $this, 'filter' ) );

        // Listing only method names

        $methods = array_map( array( $this, 'simplify' ), $methods );

        // Properties

        if( is_null( $properties ) ) {
            $properties = $reflector -> getProperties( \ReflectionProperty::IS_PROTECTED );
        }

        $properties = array_filter( $properties, array( $this, 'filter' ) );

        $properties = array_map( array( $this, 'simplify' ), $properties );

        // Injecting Caller Object into Callee

        try {

            $property = $reflector -> getproperty( sprintf( '_%s', strtolower( $caller ) ) );

            $property -> setAccessible( TRUE );

            $property -> setValue( $invoker -> getCallee(), $caller );

        } catch( \ReflectionException $e ) {
            // Silenced because we'll only inject if the property exist
        }

        // Building Context Structure

        $this -> callables[ $caller -> getClass() -> getName() ][] = array(
            ( $callee instanceof Mimicker ? $callee -> getMimicker() : $callee ), $methods, $properties
        );

        return $this;
    }

    /**
     * Invoke an extended resource from a caller context
     *
     * @param \Next\Components\Object $caller
     *  Caller Object
     *
     * @param string $method
     *  Callable resource name
     *
     * @param array $args
     *  Calling Arguments
     *
     * @return mixed|boolean
     *  Return what extended method returns.
     *
     *  If invoking process fail, false will returned.
     *
     * @throws \Next\Components\Debug\Exception
     *  Called resource is not known as an extended method
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

        /**
         * Unknown Method
         *
         * @internal
         *
         * In fact, because of the Prototype feature, triggered in \Next\Components\Object::__call()
         * when a generic Exception is thrown, this Exception will never be caught or seen
         */
        throw ContextException::methodNotFound( $method );
    }

    /**
     * Get value of a protected property from caller context
     *
     * @param \Next\Components\Object $caller
     *  Caller Object
     *
     * @param string $property
     *  Property trying to be accessed
     *
     * @return mixed
     *  The value of the property
     */
    public function get( Object $caller, $property ) {

        $caller = $caller -> getClass() -> getName();

        if( array_key_exists( $caller, $this -> callables ) ) {

            $offset = ArrayUtils::search(

                $this -> callables[ $caller ], $property
            );

            if( $offset != -1 ) {

                try {

                    $reflector = new \ReflectionProperty(

                        $this -> callables[ $caller ][ $offset ][ 0 ], $property
                    );

                    $reflector -> setAccessible( TRUE );

                    return $reflector -> getValue( $this -> callables[ $caller ][ $offset ][ 0 ] );

                } catch( \ReflectionException $e ) {

                    // Unable to access chosen property

                    throw ContextException::propertyFailure( $property, $caller );
                }

            } else {

                // Unknown Property

                throw ContextException::propertyNotFound( $property, $caller, FALSE );
            }

        } else {

            /**
             * Unknown Caller
             *
             * @internal
             * This Exception exist for debugging purposes only
             */
            throw \Next\Components\Debug\Exception::wrongUse(

                'Object <strong>%s</strong> could not be recognized as a valid extended context',

                array( $caller )
            );
        }
    }

    /**
     * Set value to a protected property from caller context
     *
     * @param \Next\Components\Object $caller
     *  Caller Object
     *
     * @param string $property
     *  Property trying to be changed
     *
     * @param mixed $value
     *  New value for the property
     */
    public function set( Object $caller, $property, $value ) {

        $caller = $caller -> getClass() -> getName();

        if( array_key_exists( $caller, $this -> callables ) ) {

            $offset = ArrayUtils::search(

                $this -> callables[ $caller ], $property
            );

            if( $offset != -1 ) {

                try {

                    $reflector = new \ReflectionProperty(

                        $this -> callables[ $caller ][ $offset ][ 0 ], $property
                    );

                    $reflector -> setAccessible( TRUE );

                    $reflector -> setValue( $this -> callables[ $caller ][ $offset ][ 0 ], $value );

                } catch( \ReflectionException $e ) {

                    // Unable to modify chosen property

                    throw ContextException::propertyFailure( $property, $caller, FALSE );
                }

            } else {

                // Unknown Property

                throw ContextException::propertyNotFound( $property, $caller );
            }

        } else {

            // Unknown Caller

            throw ContextException::callerNotFound( $caller );
        }
    }

    /**
     * Get Context Callables
     *
     * @return array
     *  Registered Context Callables
     */
    public function getCallables() {
        return $this -> callables;
    }

    // Auxiliary Methods

    /**
     * Filter elements of an array to not be a method of:
     *
     * - \Next\Components\Object
     * - \Next\Components\Context
     * - \Next\Components\Prototype
     * - Any magic method (__get(), __set()...)
     *
     * @param string|ReflectionMethod|ReflectionProperty $element
     *  The current element in the "behind the scenes" loop of array_filter()
     *
     * @return boolean
     *  TRUE if current element is not a ReflectionMethod or ReflectionProperty object -OR-
     *  if current element IS a ReflectionMethod or ReflectionProperty object -AND-
     *  is not a method of one of the classes mentioned above
     *
     *  FALSE otherwise
     */
    private function filter( $element ) {

        if( ! $element instanceof \ReflectionMethod && ! $element instanceof \ReflectionProperty  ) {
            return TRUE;
        }

        return (

            ( $element -> class !== 'Next\Components\Object' ) &&

            ( $element -> class !== 'Next\Components\Context' ) &&

            ( $element -> class !== 'Next\Components\Prototype' ) && // EXPERIMENTAL

            ( substr( $element -> name, 0, 2 ) !== '__' )
        );
    }

    /**
     * Get method name from ReflectionMethod and ReflectionProperty objects
     *
     * @param string|reflectionMethod|ReflectionProperty $element
     *  The current element in the "behind the scenes" loop of array_map()
     *
     * @return string
     *  If current element is an instance of ReflectionMethod or ReflectionProperty then the
     *  value of their public property 'name' will be returned
     *
     *  If the current element is not an object of neither of these classes, it will be returned "as is"
     */
    private function simplify( $element ) {

        if( ! $element instanceof \ReflectionMethod && ! $element instanceof \ReflectionProperty  ) {
            return $element;
        }

        return $element -> name;
    }
}