<?php

/**
 * The Object Class | Components\Object.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components;

use Next\Components\Interfaces\Contextualizable;    # Contextualizable Interface
use Next\Components\Interfaces\Informational;       # Informational Interface
use Next\Components\Interfaces\Parameterizable;     # Informational Interface

/**
 * The Object is the base Class of (almost) every class in Next Framework.
 *
 * It implements the **Extended Context** Concept defined by Contextualizable
 * Interface through which an Object can bridge methods of multiple
 * classes to themselves, simulating multiple inheritance.
 *
 * It also implements the **Informational Messages Concept** defined by
 * Informational Interface through which an Object can pass on
 * Error, Information and Success Messages.
 *
 * Last but not least, it implements the **Parameterizable Concept** through
 * which an Object can have multiple Class Parameters without the need of
 * constructor overwriting (pretty much like JavaScript Object arguments),
 * reinforcing the **Additional Initialization Concept**
 *
 * @package    Next\Components
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
     * @var \Next\Components\Context $context
     */
    private $context;

    /**
     * Object Parameters
     *
     * @var \Next\Components\Parameter $options
     */
    protected $options;

    /**
     * Object Constructor
     *
     * @param mixed|optional $options
     *  Child class additional options
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

        $obj = new Parameter;

        foreach( $param as $k => $v ) {

            if( strlen( $k ) == 0 ) {

                throw ComponentsException::mapping(
                    'Although accepted as valid by PHP, all dimensions must have a key'
                );
            }

            if( is_array( $v ) ) {

                $keys = array_keys( $v );

                $na = count( array_filter( $keys, 'is_string') );
                $ni = count( array_filter( $keys, 'is_int') );

                if( $na > 0 && $ni > 0 ) {

                    throw ComponentsException::mapping(
                        'Mixed associative and indexed content is not allowed'
                    );
                }

                // Mapping associative arrays recursively

                if( $na > 0 ) {

                    $obj -> {$k} = self::map( $v );

                } else {

                    // Keeping indexed arrays as defined

                    $obj -> {$k} = $v;
                }

            } else {

                // Keeping non-array values untouched

                $obj -> {$k} = $v;
            }
        }

        return $obj;
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
     * @return \Next\Components\Object
     *  Object Instance (Fluent Interface)
     *
     * @see \Next\Components\Context::extend()
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
     * @see \Next\Components\Context::getCallables()
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
    public function setOptions() {}

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
     *  is caught in \Next\Components\Context::call()
     *
     * @throws \Next\Components\ComponentsException
     *  Object Constructor was overwritten without invoking it through
     *  parent context instead of using the \Next\Components\Object::init()
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
     * @throws \Next\Components\ComponentsException
     *  Object Constructor was overwritten without invoking it through
     *  parent context instead of using the \Next\Components\Object::init()
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
     * @throws \Next\Components\ComponentsException
     *  Object Constructor was overwritten without invoking it through
     *  parent context instead of using the \Next\Components\Object::init()
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