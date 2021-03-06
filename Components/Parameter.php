<?php

/**
 * Parameterizable Component Class | Components\Parameter.php
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
use Next\Exception\Exceptions\InvalidArgumentException;
use Next\Exception\Exceptions\LogicException;
use Next\Exception\Exceptions\RuntimeException;
use Next\Exception\Exceptions\AccessViolationException;
use Next\Exception\Exceptions\UnderflowException;

use Next\Validation\Verifiable;    # Verifiable Interface

/**
 * Data-structure for classes' Parameter Options as part of the
 * Parameterizable Concept
 *
 * @package    Next\Components
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Exception\Exceptions\LogicException
 *             Next\Exception\Exceptions\RuntimeException
 *             Next\Exception\Exceptions\AccessViolationException
 *             Next\Exception\Exceptions\UnderflowException
 *             Next\Validation\Verifiable
 *             Next\Components\Object
 *             Countable
 *             ArrayAccess
 *             stdClass
 */
class Parameter implements Verifiable, \Countable, \ArrayAccess {

    /**
     * Object Parameters
     *
     * @var stdClass $parameters
     */
    private $parameters;

    /**
     * Default Options.
     * Default Options are usually defined in abstract classes being
     * available for all its children
     *
     * @var array|Next\Components\Parameter
     */
    private $defaultOptions;

    /**
     * Custom Options.
     * Custom Options are defined in concrete children classes affecting
     * individually the working mode of each class
     *
     * @var array|Next\Components\Parameter
     */
    private $customOptions;

    /**
     * Instance Options.
     * These are defined when the Object is instantiated, allowing the
     * User to modify any of the already defined Options
     *
     * @var array|Next\Components\Parameter
     */
    private $instanceOptions;

    /**
     * Parameter Object Constructor
     * Lists possible Parameter Options to build recursively the
     * combined structure verifying its integrity after that
     */
    public function __construct() {

        list( $this -> defaultOptions, $this -> customOptions, $this -> instanceOptions ) =
            func_get_args() + [ NULL, NULL, NULL ];

        $this -> parameters = new \stdClass;

        // Merging Parameter Options

        $this -> merge( $this -> defaultOptions );

        $this -> merge( $this -> customOptions );

        $this -> merge( $this -> instanceOptions );

        // Discarding unused informations

        $this -> discard();

        // Verifying Parameter Options Integrity

        $this -> verify();
    }

    /**
     * Merge Options
     *
     * @param array|Next\Components\Parameter $parameter
     *  An associative array with Parameter Options or a full formed Parameter Object
     *
     * @throws Next\Components\Exception\InvalidArgumentException
     *  Thrown if a Parameter Option predefined to be of an specific
     *  Object instance it of a different type, simulating type-hinting
     */
    public function merge( $parameter ) : void {

        if( $parameter === NULL ) return;

        if( (array) $parameter === $parameter ) $parameter = Object::map( $parameter );

        if( $parameter instanceof Parameter ) {
            $this -> merge( $parameter -> getParameters() ); return;
        }

        // Merging...

        foreach( $parameter as $name => $value ) {

            $name = trim( $name );

            if( isset( $this -> parameters -> {$name} ) ) {

                /**
                 * @internal
                 *
                 * Because Instance Options are the last ones to be
                 * added to the Parameters Object, overwriting any
                 * Options with the same name, it's possible to
                 * simulate a type-hinting by defining a structure
                 * with a FQCN namespace as its value.
                 *
                 * For example, considering a class children of
                 * \Next\Components\Object having overwritten
                 * Object::$defaultOptions as follows:
                 *
                 * ````
                 * <?php
                 *
                 * class User extends Object {
                 *
                 *     protected $defaultOptions = [
                 *        'UserName' => [ 'type' => Next\Components\Types\Strings' ]
                 *     ];
                 * }
                 * ````
                 *
                 * When this Object is instantiated, if a Parameter Option
                 * named 'UserName' is defined like this:
                 *
                 * ````
                 * $user = new User( [ 'UserName' => new Strings( 'Rumplestiltskin' ) ] );
                 * ````
                 *
                 * The Parameter Option will be accepted because the
                 * value passed is indeed an instance of
                 * \Next\Components\Types\Strings
                 *
                 * But if it's instead created like this:
                 *
                 * ````
                 * $user = new User( [ 'UserName' => 'Rumplestiltskin' ] );
                 * ````
                 *
                 * Even though 'Rumplestiltskin' is a string it's not
                 * an Object instance of the expected class and thus
                 * won't be accepted throwing an InvalidArgumentException
                 */
                if( isset( $this -> parameters -> {$name} -> type ) ) {

                    if( ! $value instanceof $this -> parameters -> {$name} -> type ) {

                        throw new InvalidArgumentException(

                            sprintf(

                                'Parameter Option <strong>%1$s</strong>
                                must be an instance of <em>%2$s</em>',

                                $name, $this -> parameters -> {$name} -> type
                            )
                        );
                    }
                }
            }

            $this -> parameters -> {$name} = $value;
        }
    }

    // Accessors

    /**
     * Get all Parameters as structurally defined
     *
     * @return stdClass
     *  All Parameters merged together in a stdClass structure
     */
    public function getParameters() : \stdClass {
        return $this -> parameters;
    }

    // Verifiable Interface Method Implementation

    /**
     * Verify Parameter Options Integrity
     *
     * @throws Next\Exception\Exceptions\LogicException
     *  Thrown if a Parameter Option marked as 'required' wasn't
     *  overwrote with the real value
     */
    public function verify() : void {

        foreach( $this-> parameters as $name => $parameter ) {

            /**
             * @internal
             *
             * Closure objects cannot have properties so the very test below
             * results in a Fatal Error so Closures won't be discarded
             */
            if( $parameter instanceof \Closure ) continue;

            if( isset( $parameter -> required ) && $parameter -> required !== FALSE ) {

                throw new LogicException(

                    sprintf(

                        'Missing required Parameter Option <strong>%s</strong>',

                        $name
                    )
                );
            }
        }
    }

    // Countable Interface Method Implementation

    /**
     * Count Parameters Options
     *
     * @return integer
     *  Number of Options set
     *
     * @see Countable::count()
     */
    public function count() : int {
        return count( get_object_vars( $this -> parameters ) );
    }

    // ArrayAccess Interface Methods Implementation

    /**
     * Adds a new value to Parameter Object
     *
     * @param string $identifier
     *  String identifier for the value
     *
     * @param mixed $value
     *  Parameter value
     *
     * @throws new Next\Exception\Exceptions\InvalidArgumentException
     *  Throw if missing associative indexes with non-associative
     */
    public function offsetSet( $identifier, $value ) : void {

        if( (array) $value === $value ) {

            /**
             * @internal
             *
             * The only way for Next\Components\Object::map() to
             * handle the integrity checking below is to NOT condition
             * Parameter::offsetSet() routine to be or not an array
             *
             * But removing this condition the innermost values of each
             * individual parameter, the ones that really serves as
             * configuration for something instead of just define a
             * complex structure, *may* not be an array at some point
             * and thus the foreach in Object::map() would fail
             *
             * By restricting Object::map() exclusively to traversable
             * resources is not very reliable as well because this
             * checking is based upon keys of an array and
             * Traversable Objects may not have them
             *
             * In any event, it's safer repeating the checking
             * here as well ;)
             */
            $keys = array_keys( $value );

            $na = count( array_filter( $keys, 'is_string') );
            $ni = count( array_filter( $keys, 'is_int') );

            if( $na > 0 && $ni > 0 ) {

                throw new InvalidArgumentException(
                    'Mixed associative and indexed content is not allowed'
                );
            }

            // Mapping associative arrays recursively

            if( $na > 0 ) {

                $this -> parameters -> {$identifier} = Object::map( $value );

            } else {

                // Keeping indexed arrays as defined

                $this -> parameters -> {$identifier} = $value;
            }

        } else {

            // Keeping non-array values untouched

            $this -> parameters -> {$identifier} = $value;
        }
    }

    /**
     * Checks if a Parameter Identifier exists
     *
     * @param string $identifier
     *  Parameter Identifier
     *
     * @return boolean
     *  TRUE if Parameter Identifier exists and FALSE otherwise
     */
    public function offsetExists( $identifier ) : bool {
        return ( property_exists( $this -> parameters, $identifier ) );
    }

    /**
     * Removes a Parameter Identifier
     *
     * @param string $identifier
     *  Parameter Identifier
     *
     * @throws \Next\Exception\Exceptions\AccessViolationException
     *  Thrown if trying to remove a Identifier that doesn't exist
     *
     * @throws \Next\Exception\Exceptions\UnderflowException
     *  Thrown if trying to remove a Parameter Option when there are none defined
     */
    public function offsetUnset( $identifier ) : void {

        if( count( $this ) == 0 ) {
            throw new UnderflowException( 'There are no Parameters to remove' );
        }

        if( ! isset( $this -> parameters -> {$identifier} ) ) {

            throw new AccessViolationException(

                sprintf(

                    'Parameter Option <strong>%s</strong> doesn\'t exist
                    and therefore cannot be removed',

                    $identifier
                )
            );
        }

        unset( $this -> parameters -> {$identifier} );
    }

    /**
     * Grants access to Parameter Identifiers
     *
     * @param string $identifier
     *  Parameter Identifier
     *
     * @return mixed
     *  Parameter value
     */
    public function offsetGet( $identifier ) {

        if( ! isset( $this -> parameters -> {$identifier} ) ) return NULL;

        if( $this -> parameters -> {$identifier} instanceof Parameter ) {

            return $this -> parameters -> {$identifier} -> getParameters();

        } else {

            return $this -> parameters -> {$identifier};
        }
    }

    // Overloading

    /**
     * Checks if a Parameter Identifier exists
     *
     * @param string $identifier
     *  Parameter Identifier
     *
     * @return boolean
     *  TRUE if Parameter Identifier exists and FALSE otherwise
     *
     * @see Parameter Parameter::offsetExists()
     */
    public function __isset( $identifier ) : bool {
        return $this -> offsetExists( $identifier );
    }

    /**
     * Removes a Parameter Identifier
     *
     * @param string $identifier
     *  Parameter Identifier
     *
     * @throws \Next\Exception\Exceptions\AccessViolationException
     *  Thrown if trying to remove a Identifier that doesn't exist
     *
     * @see Parameter::offsetUnset()
     */
    public function __unset( $identifier ) :void {
        $this -> offsetUnset( $identifier );
    }

    /**
     * Adds a new value to Parameter Object
     *
     * @param string $identifier
     *  String identifier for the value
     *
     * @param mixed $value
     *  Parameter value
     *
     * @see Parameter::offsetSet()
     */
    public function __set( $identifier, $value ) : void {
        $this -> offsetSet( $identifier, $value );
    }

    /**
     * Grants access to Parameter Identifiers
     *
     * @param string $identifier
     *  Parameter Identifier
     *
     * @return mixed
     *  Parameter value
     *
     * @see Parameter::offsetGet()
     */
    public function __get( $identifier ) {
        return $this -> offsetGet( $identifier );
    }

    // Auxiliary Methods

    /**
     * Discards pseudo-type-hinting informations from optional
     * Parameter Options - otherwise Parameter::merge() would've raised
     * an Exception - overwriting them with a Default Value, if provided
     * otherwise with NULL
     *
     * @throws Next\Components\Exception\RuntimeException
     *  Throws if an \Exception is caught when instantiating the object
     *  defined in 'type' entry
     */
    private function discard() : void {

        /**
         * @isee Next\Components\Objects::__construct() for further explanations
         */
        if( defined( 'DEVELOPMENT_MODE' ) && DEVELOPMENT_MODE == 1 ) {

            unset( $this -> defaultOptions, $this -> customOptions, $this -> instanceOptions );
        }

        foreach( $this -> parameters as $name => $parameter ) {

            /**
             * @internal
             *
             * Closure objects cannot have properties so the very test below
             * results in a Fatal Error so Closures won't be discarded
             */
            if( $parameter instanceof \Closure ) continue;

            if( isset( $parameter -> type ) ) {

                if( ! isset( $parameter -> default ) || ( $default = $parameter -> default ) === NULL ) {
                    $this -> parameters -> {$name} = NULL; continue;
                }

                try {

                    $reflector = new \ReflectionClass( $parameter -> type );

                    if( $reflector -> isInstantiable() ) {
                        $this -> parameters -> {$name} = new $parameter -> type( $default );
                    } else {
                        $this -> parameters -> {$name} = $default;
                    }

                } catch( \ReflectionException $e ) {

                    $this -> parameters -> {$name} = $default;

                } catch( \Exception $e ) {

                    throw new RuntimeException(

                        sprintf(

                            'Unable to instantiate object <strong>%s</strong>
                            defined as default value of <strong>%s</strong>
                            Parameter Option

                            The following error has been returned: %s',

                            $parameter -> type, $name, $e -> getMessage()
                        )
                    );
                }

            } else {

                if( isset( $parameter -> default ) ) {
                    $this -> parameters -> {$name} = $parameter -> default;
                }
            }
        }
    }
}
