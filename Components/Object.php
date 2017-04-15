<?php

namespace Next\Components;

use Next\Components\Interfaces\Contextualizable;    # Contextualizable Interface
use Next\Components\Interfaces\Informational;       # Informational Interface
use Next\Components\Interfaces\Parameterizable;     # Informational Interface

/**
 * Next Object Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Object extends Prototype implements Contextualizable, Informational, Parameterizable {

    /**
     * Default Options. Must be overwritten
     *
     * @var array $defaultOptions
     */
    protected $defaultOptions = array();

    /**
     * Success Message
     *
     * @var mixed|string $_success
     */
    protected $_success;

    /**
     * Error Message
     *
     * @var mixed|string $_error
     */
    protected $_error;

    /**
     * Informational Message
     *
     * @var mixed|string $_info
     */
    protected $_info;

    /**
     * Context Object
     *
     * @var Next\Components\Context $context
     */
    private $context;

    /**
     * Object Parameters
     *
     * @var Next\Components\Parameter $options
     */
    protected $options;

    /**
     * Object Constructor
     *
     * @param mixed|optional $defaultOptions
     *  Child class default options
     */
    public function __construct( $options = NULL ) {

        $this -> context = new Context;

        $this -> options = new Parameter( $this -> defaultOptions, $this -> setOptions(), $options );

        $this -> init();
    }

    /**
     * Additional initialization. Must be overwritten
     */
    protected function init() {}

    /**
     * Map given array to stdClass Object recursively
     *
     * @param mixed $param
     *  Argument to mapped into an stdClass Object
     *
     * @return stdClass
     *  Given argument mapped into an stdClass Object
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
     *  Reflector instance of Object
     */
    public function getClass() {
        return new \ReflectionObject( $this );
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
        return spl_object_hash( $this );
    }

    // Contextualizable Interface Methods Implementation

    /**
     * Register a new Invoker Object to be used as context extension
     *
     * @param Next\Components\Invoker $invoker
     *  Invoker Object
     *
     * @param string|array|optional $methods
     *  One or more methods accessible through extended Context.
     *  Defaults to NULL, which means almost all PUBLIC methods will be accessible
     *
     *   @param string|array|optional $properties
     *  One or more properties accessible through extended Context
     *  Defaults to NULL, which means all PROTECTED properties will be accessible
     *
     * @return Next\Components\Object
     *  Object Instance (Fluent Interface)
     *
     * @see Next\Components\Context::extend()
     */
    final public function extend( Invoker $invoker, $methods = NULL, $properties = NULL ) {

        if( is_null( $this -> context ) ) {
            throw ComponentsException::extendedContextFailure( $this );
        }

        $this -> context -> extend( $invoker, $methods, $properties );

        return $this;
    }

    /**
     * Get Context Callables
     *
     * @return array
     *  Registered Context Callables
     *
     * @see Next\Components\Context::getCallables()
     */
    public function getCallables() {
         return $this -> context -> getCallables();
    }

    // Informational Interfaces Methods Implementation

    /**
     * Get success message
     *
     * @return string
     *  Success Message
     */
    public function getSuccessMessage() {
        return $this -> _success;
    }

    /**
     * Get error message
     *
     * @return string
     *  Error Message
     */
    public function getErrorMessage() {
        return $this -> _error;
    }

    /**
     * Get informational message
     *
     * @return string
     *  Informational Message
     */
    public function getInformationalMessage() {
        return $this -> _info;
    }

    // Parameterizable Methods Interfaces

    /**
     * Get Class Options
     */
    public function getOptions() {
        return $this -> options;
    }

    /**
     * Set Class Options
     * It's not really implemented because not all child classes have something to be configured
     */
    public function setOptions( $options = array() ) {}

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
     *  Method trying to be invoked
     *
     * @param array|optional $args
     *  Variable list of arguments to the method
     *
     * @return mixed|boolean
     *  Return what extended method returns or FALSE if a ReflectionException
     *  is caught in Next\Components\Context::call()
     *
     * @throws Next\Components\ComponentsException
     *  Object Constructor was overwritten without invoking it through
     *  parent context instead of using the Next\Components\Object::init()
     */
    public function __call( $method, array $args = array() ) {

        if( is_null( $this -> context ) ) {
            throw ComponentsException::extendedContextFailure( $method, $this );
        }

        // Trying to call as an extended method

        try {

            return $this -> context -> call( $this, $method, $args );

        } catch( ContextException $e ) {

            if( $e -> getCode() == ContextException::METHOD_NOT_FOUND ) {

                // Trying to call as a prototyped method

                return $this -> call( $this, $method, $args );
            }
        }
    }

    /**
     * Allow change of properties from extended Objects in this Object context
     *
     * IMPORTANT!
     *
     * In order to be considered a property of an extended context,
     * properties must be prefixed with an underscore, even if they don't
     * have one in their original classes
     *
     * @param string $property
     *  Property trying to be changed
     *
     * @param mixed $value
     *  New value for the property
     *
     * @throws Next\Components\ComponentsException
     *  Object Constructor was overwritten without invoking it through
     *  parent context instead of using the Next\Components\Object::init()
     */
    public function __set( $property, $value ) {

        if( is_null( $this -> context ) ) {

            throw ComponentsException::overloadedPropertyUpdateFailure(
                $property, $this
            );
        }

        // Only properties prefixed with an underscore will be considered for extended context

        if( substr( $property, 0, 1 ) == '_' ) {
            $this -> context -> set( $this, str_replace( '_', '', $property ), $value );
        }
    }

    /**
     * Allow retrieval of properties from extended Objects in this Object context
     *
     * IMPORTANT!
     *
     * In order to be considered a property of an extended context,
     * properties must be prefixed with an underscore, even if they
     * don't have one in their original classes
     *
     * @param string $property
     *  Property trying to be retrieved
     *
     * @throws Next\Components\ComponentsException
     *  Object Constructor was overwritten without invoking it through
     *  parent context instead of using the Next\Components\Object::init()
     */
    public function __get( $property ) {

        if( is_null( $this -> context ) ) {

            throw ComponentsException::overloadedPropertyReadingFailure(
                $property, $this
            );
        }

        // Only properties prefixed with an underscore will be considered for extended context

        if( substr( $property, 0, 1 ) == '_' ) {
            return $this -> context -> get( $this, str_replace( '_', '', $property ) );
        }
    }

    /**
     * Return a nice name for the class
     *
     * @return string
     *  Classname without namespaces
     */
    public function __toString() {
        return $this -> getClass() -> getShortName();
    }
}