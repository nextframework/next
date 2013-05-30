<?php

namespace Next\Components;

use Next\Components\Interfaces\Contextualizable;      # Contextualizable Interface

/**
 * Next Object Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Object extends Prototype implements Contextualizable {

    /**
     * Context Object
     *
     * @var Next\Components\Context $context
     */
    private $context;

    /**
     * Object Constructor
     */
    public function __construct() {

        $this -> context = new Context;
    }

    /**
     * Map given array to stdClass Object recursively
     *
     * @param mixed $param
     *   Argument to mapped into an stdClass Object
     *
     * @return stdClass
     *   Given argument mapped into an stdClass Object
     */
    public static function map( $param ) {

        if( is_array( $param ) ) {
            return (object) array_map( __METHOD__, $param );
        }

        return $param;
    }

    // Accessors

    /**
     * Retrieves a Reflection instance for the Object
     *
     * @return ReflectionClass
     *   Reflector instance of Object
     */
    public function getClass() {
        return new \ReflectionClass( get_class( $this ) );
    }

    /**
     * Retrieves Object Hash
     *
     * Child classes may overwrite this method in order to implement their own way
     * to define a class hash
     *
     * @return string Object Hash
     */
    public function getHash() {
        //return md5( get_class( $this ) );
        return spl_object_hash( $this );
    }

    // Contextualizable Interface Methods Implementation

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
     * @return Next\Components\Object
     *   Object Instance (Fluent Interface)
     *
     * @see Next\Components\Context::extend()
     */
    public function extend( Invoker $invoker, $methods = NULL ) {

        $this -> context -> extend( $invoker, $methods );

        return $this;
    }

    /**
     * Get Context Callables
     *
     * @return array
     *   Registered Context Callables
     *
     * @see Next\Components\Context::getCallables()
     */
    public function getCallables() {
         return $this -> context -> getCallables();
    }

    // OverLoading

    /**
     * Allow extended Objects to be called in this Object context
     *
     * IMPORTANT!
     *
     * Watch out when use Fluent Interfaces, because the "bridging"
     * feature WILL return the METHOD called, not the invoker object
     *
     * @param string $method
     *   Method trying to be invoked
     *
     * @param array|optional $args
     *   Variable list of arguments to the method
     *
     * @return mixed|boolean
     *   Return what extended method returns
     *
     * @throws Next\Components\Debug\Exception
     *   Object Constructor was overwritten without invoking it through
     *   parent:: context
     */
    public function __call( $method, array $args = array() ) {

        if( is_null( $this -> context ) ) {

            throw \Next\Components\Debug\Exception::unfullfilledRequirements(

                '<p>
                    Method <strong>%s</strong> is not known by
                    <strong>%s</strong> and was not trapped by
                    <em>__call()</em>.
                </p>

                <p>
                    Could you possibly overwrote <em>Object::__construct()</em>
                    without invoke it under parent context?
                </p>',

                array( $method, $this )
            );
        }

        // Trying to call as an extended method

        try {

            return $this -> context -> call( $this, $method, $args );

        } catch( \Next\Components\Debug\Exception $e ) {

            // Trying to call as a prototyped method

            return $this -> call( $this, $method, $args );
        }
    }

    /**
     * Return a nice name for the class
     *
     * @return string
     *   Classname without namespaces
     */
    public function __toString() {
        return $this -> getClass() -> getShortName();
    }
}