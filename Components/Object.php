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

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\NullException;

use Next\Components\Interfaces\Hashable;            # Hashable Interface
use Next\Components\Interfaces\Contextualizable;    # Contextualizable Interface
use Next\Components\Interfaces\Informational;       # Informational Interface
use Next\Components\Interfaces\Parameterizable;     # Parameterizable Interface
use Next\Validation\Verifiable;                     # Verifiable Interface

/**
 * The Object is the base Class of (almost) all classes in Next Framework.
 *
 * It implements the **Extended Context** Concept defined by
 * Contextualizable Interface through which an Object can bridge methods
 * of multiple classes to themselves, simulating multiple inheritance.
 *
 * It also implements the **Informational Messages Concept** defined by
 * Informational Interface through which an Object can retrieve passed
 * Error, Information and/or Success Messages.
 *
 * Objects implements the **Parameterizable Concept** through which an
 * Object can have multiple Parameters Options replacing arguments
 * usually defined in the Constructor without the need of
 * overwrite it, similarly to JavaScript Object arguments,
 * reinforcing the **Additional Initialization Concept**
 *
 * Last but not least, the **Verifiable Concept** defined by
 * Verifiable Interface through which an Object enforces an integrity
 * checking of any sorts, but usually to reinforce the
 * Parameterizable Concept that (currently) lacks the typed verifications
 * the way type-hinting does
 *
 * @package    Next\Components
 *
 * @uses       Next\Exception\Exceptions\NullException
 *             Next\Components\Prototype
 *             Next\Components\Interfaces\Hashable
 *             Next\Components\Interfaces\Contextualizable
 *             Next\Components\Interfaces\Informational
 *             Next\Components\Interfaces\Parameterizable
 *             Next\Validation\Verifiable
 *             Next\Components\Context
 *             Next\Components\Parameter
 *             ReflectionObject
 */
class Object extends Prototype implements Hashable, Contextualizable, Informational, Parameterizable {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [];

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
     * Object Constructor.
     *
     * - Creates the Extended Context
     * - Merges all Parameter Options in one
     * - Runs Additional Initialization
     * - Calls Next\Validation\Verifiable::verify() if needed
     *
     * Phew!
     *
     * @param mixed|optional $options
     *  Child class additional options
     */
    public function __construct( $options = NULL ) {

        $this -> context = new Context;

        $this -> options = new Parameter( $this -> parameters, $this -> setOptions(), $options );

        if( $this instanceof Verifiable ) $this -> verify();

        $this -> init();
    }

    /**
     * Additional initialization. Must be overwritten
     */
    protected function init() : void {}

    /**
     * Map given array to stdClass Object recursively
     *
     * @param mixed $param
     *  Argument to mapped into an stdClass Object
     *
     * @return stdClass
     *  Given argument mapped into an stdClass Object
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown when an entry of the associative array being mapped
     *  has an empty key. E.g:
     *
     * ````
     * $array = [ '' => 'foo' ];
     * ````
     *
     * This is accepted by PHP (for whatever reason), but we don't accept
     */
    public static function map( $param ) : Parameter {

        $obj = new Parameter;

        foreach( $param as $k => $v ) {

            if( strlen( $k ) == 0 ) {

                throw new RuntimeException(
                    'Although accepted as valid by PHP, all dimensions must have a key'
                );
            }

            if( (array) $v === $v ) {

                $keys = array_keys( $v );

                $na = count( array_filter( $keys, 'is_string') );

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

    // Accessory Methods

    /**
     * Retrieves a Reflection instance for the Object
     *
     * @return ReflectionClass
     *  Reflector instance of Object
     */
    public function getClass() : \ReflectionObject {
        return new \ReflectionObject( $this );
    }

    // Hashable Interface Method Implementation

    /**
     * Get Object hash
     *
     * @internal
     *
     * Child classes may overwrite this method in order to implement their own
     * way to define an object hash but they are bound to return a string
     *
     * @return string Object Hash
     */
    public function hash() : string {
        return spl_object_hash( $this );
    }

    // Contextualizable Interface Methods Implementation

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
     * @param string|array|optional $properties
     *  One or more properties accessible through extended Context
     *  Defaults to NULL, which means all PROTECTED properties will be accessible
     *
     * @return \Next\Components\Object
     *  Object Instance (Fluent Interface)
     *
     * @see \Next\Components\Context::extend()
     */
    final public function extend( Invoker $invoker, $methods = NULL, $properties = NULL ) : Object {

        if( $this -> context === NULL ) {
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
    public function getCallables() : array {
         return $this -> context -> getCallables();
    }

    // Informational Interface Methods Implementation

    /**
     * Get success message
     *
     * @return string
     *  Success Message
     */
    public function getSuccessMessage() :? string {
        return $this -> _success;
    }

    /**
     * Get error message
     *
     * @return string
     *  Error Message
     */
    public function getErrorMessage() :? string {
        return $this -> _error;
    }

    /**
     * Get informational message
     *
     * @return mixed
     *  Informational Message.
     *  Because it's flexible it can be a simple string or an array of informations
     */
    public function getInformationalMessage() {
        return $this -> _info;
    }

    // Parameterizable Interface Methods Implementation

    /**
     * Get Class Options
     *
     * @return \Next\Components\Parameter
     *  Parameter Object with all Default, Custom (inherited)
     *  and Instance Parameter Options merged
     */
    public function getOptions() : Parameter {
        return $this -> options;
    }

    /**
     * Set class options
     *
     * @internal
     *
     * It's not really implemented because not all child classes
     * have something to be configured
     *
     * @return \Next\Components\Parameter|array|void
     *  If overwritten, it must return an array or a well-formed
     *  instance of Next\Components\Parameter.
     *  Otherwise, nothing is returned
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
     * @return mixed|void
     *  Returns what the method under Extended Context returns or
     *  what the prototyped resource returns
     *  Any failure on either of these calls and nothing is returned
     *
     * @throws \Next\Exception\Exceptions\BadmethodcallException
     *  Thrown if a \ReflectionException is caught in when trying
     *  to call the method under Extended Context
     *
     * @throws \Next\Exception\Exceptions\BadmethodcallException
     *  Thrown if the method couldn't be found as part of an
     *  Extended Context and neither as a Prototyped Resource
     */
    public function __call( $method, array $args = [] ) {

        // Trying to call as an extended method

        try {

            return $this -> context -> call( $this, $method, $args );

        } catch( NullException $e ) {

            // Trying to call as a prototyped method

            return $this -> call( $this, $method, $args );
        }
    }

    /**
     * Return a nice name for the class
     *
     * @return string
     *  Classname without namespaces
     */
    public function __toString() : string {
        return $this -> getClass() -> getShortName();
    }
}