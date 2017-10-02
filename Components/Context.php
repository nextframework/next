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

/**
 * Exception Class(es)
 */
use Next\Components\Interfaces\Contextualizable;
use Next\Exception\Exceptions\BadMethodCallException;
use Next\Exception\Exceptions\NullException;

use Next\Components\Invoker;             # Invoker Class
use Next\Components\Utils\ArrayUtils;    # Array Utils Class

/**
 * Bridges two \Next\Components\Object instances wrapped by a
 * \Next\Components\Invoker Object and handles methods calling
 * within the Extended Context
 *
 * @package    Next\Components
 */
class Context implements Contextualizable {

    /**
     * Context Callables
     *
     * @var array $callables
     */
    private $callables = [];

    /**
     * Registers a new Invoker Object to be used as context extension
     *
     * @param \Next\Components\Invoker $invoker
     *  Invoker Object
     *
     * @param string|array|optional $methods
     *  One or more methods accessible through extended Context.
     *  Defaults to NULL, which means almost all PUBLIC methods will be accessible
     *
     * @return \Next\Components\Context
     *  Context Instance (Fluent Interface)
     */
    function extend( Invoker $invoker, $methods = NULL ) {

        $caller = $invoker -> getCaller();
        $callee = $invoker -> getCallee();

        $reflector = ( $callee instanceof Mimicker ?
                         new \ReflectionObject( $callee -> getMimicked() ) :
                            $callee -> getClass() );

        // Methods

        if( is_null( $methods ) ) {
            $methods = $reflector -> getMethods( \ReflectionMethod::IS_PUBLIC );
        }

        // Restricting access to methods of some classes to avoid infinite loops

        $methods = array_filter( $methods, [ $this, 'filter' ] );

        // Listing only method names

        $methods = array_map( [ $this, 'simplify' ], $methods );

        // Building Context Structure

        $this -> callables[ $caller -> getClass() -> getName() ][] = [
            ( $callee instanceof Mimicker ? $callee -> getMimicked() : $callee ), $methods
        ];

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
     * @throws \Next\Exception\Exceptions\BadmethodcallException
     *  Thrown if a \ReflectionException is caught in
     *  `\Next\Components\Context::call()` when trying to call the
     *  method under Extended Context
     *
     * @throws \Next\Exception\Exceptions\NullException
     *  Thrown as a way for `\Next\Components\Object` to know the
     *  method hasn't been found as part of the Extended Context and
     *  should try to search as Prototyped Resource
     */
    public function call( Object $caller, $method, array $args = [] ) {

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

                    throw new BadMethodCallException(

                        sprintf(

                            'Unable to call method <strong>%s</strong>
                            under Extended Context',

                            $method
                        )
                    );
                }
            }
        }

        /**
         * @internal
         *
         * Theoretically, here we should throw an Exception in case
         * the method couldn't be found as part of the Extended Context.
         *
         * But because we also have Prototyped Resources feature, this
         * Exception will be never seen, acting just as a hint for
         * `Next\Components\Object::__call()` to trigger the calling
         * of Prototyped Resources
         *
         * If the method can't be found there as well THEn it's a real
         * method not found scenario
         *
         * @see Next\Components\Object::__call()
         * @see Next\Components\Prototype::call()
         */
        throw new NullException( NULL );
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
     * @param string|ReflectionMethod $element
     *  The current element in the "behind the scenes" loop of array_filter()
     *
     * @return boolean
     *  TRUE if current element is NOT an instance of
     *  \ReflectionMethod -OR- if given \ReflectionObject::$class
     *  property of current element is not a method of one of the
     *  classes mentioned above and FALSE otherwise
     */
    private function filter( $element ) {

        if( ! $element instanceof \ReflectionMethod ) {
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
     * Get method name from ReflectionMethod objects
     *
     * @param string|reflectionMethod $element
     *  The current element in the "behind the scenes" loop of array_map()
     *
     * @return string
     *  If current element is an instance of ReflectionMethod then the
     *  value of their public property 'name' will be returned
     *
     *  Otherwise it will be returned "as is"
     */
    private function simplify( $element ) {

        return ( ! $element instanceof \ReflectionMethod  ) ?
                    $element : $element -> name;
    }
}