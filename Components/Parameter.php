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

use Next\Components\Debug\Exception;      # Exception Class

/**
 * Defines a data-structure for Class Options as part of the Parameterizable Concept
 *
 * @package    Next\Components
 */
class Parameter implements \Countable, \ArrayAccess {

    /**
     * Object Parameters
     *
     * @var stdClass $parameters
     */
    private $parameters;

    /**
     * Parameter Object Constructor
     *
     *  - Common Options
     *
     *  Usually defined in abstract classes these options will be available
     *  for all its children
     *
     *  - Children Options
     *
     *  Defined in concrete classes these options will affect individually
     *  the working mode of each class.
     *
     *  These options can sometimes overwrite Common Options too.
     *
     *  - User Options
     *
     *  Defined in class constructor, when instantiating the object, allow
     *  user to change one or all of the options.
     */
    public function __construct() {

        list( $common, $custom, $user ) = func_get_args() + array( NULL, NULL, NULL );

        $this -> parameters = new \stdClass;

        // Building Options (by merging them)

        if( ! is_null( $common ) ) $this -> merge( $common );

        if( ! is_null( $custom ) ) $this -> merge( $custom );

        if( ! is_null( $user ) )   $this -> merge( $user );
    }

    /**
     * Merge Options
     *
     * @param mixed $param
     *
     *  Argument to merge as Parameter Options
     *
     *  Acceptable values are:
     *
     *   <ul>
     *
     *       <li>Array</li>
     *
     *       <li>
     *
     *           An {@link http://php.net/manual/en/reserved.classes.php stdClass Object}
     *       </li>
     *
     *       <li>\Next\Components\Object object</li>
     *
     *   </ul>
     *
     * @throws \Next\Components\Debug\Exception
     *  Any option is not an associative array, because
     *  doesn't make sense to have a public property just to store NULL
     */
    public function merge( $param ) {

        // Converting, if needed

        if( is_array( $param ) ) $param = Object::map( $param );

        if( $param instanceof Parameter ) {
            return $this -> merge( $param -> getParameters() );
        }

        // Merging...

        foreach( $param as $property => $value ) {
            $this -> parameters -> {$property} = $value;
        }
    }

    // Accessors

    /**
     * Get all Parameters
     *
     * @return stdClass
     *  All Parameters merged together in a stdClass structure
     */
    public function getParameters() {
        return $this -> parameters;
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
    public function count() {
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
     */
    public function offsetSet( $identifier, $value ) {

        if( is_array( $value ) ) {

            /**
             * @internal
             *
             * The only way for \Next\Components\Object::map() to handle the
             * integrity checking below is to NOT condition Parameter::offsetSet()
             * routine to be or not an array
             *
             * But removing this condition the innermost values of each individual
             * parameter, the ones that really serves as configuration for something
             * instead of just define a complex structure, *may* not be an array
             * at some point and thus the foreach in Object::map() would fail
             *
             * Restrict Object::map() exclusively to traversable resources is not
             * very reliable as well because this checking is based upon keys of
             * an array and Traversable Objects may not have them
             *
             * In any event, it's safer repeating the checking here as well ;)
             */
            $keys = array_keys( $value );

            $na = count( array_filter( $keys, 'is_string') );
            $ni = count( array_filter( $keys, 'is_int') );

            if( $na > 0 && $ni > 0 ) {

                throw ComponentsException::mapping(
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
    public function offsetExists( $identifier ) {
        return ( isset( $this -> parameters -> {$identifier} ) );
    }

    /**
     * Removes a Parameter Identifier
     *
     * @param string $identifier
     *  Parameter Identifier
     *
     * @throws \Next\Components\Debug\Exception
     *  thrown if trying to remove a Identifier that doesn't exist
     */
    public function offsetUnset($identifier) {

        if( ! isset( $this -> parameters -> {$identifier} ) ) {

            throw Exception::logic(

                'Identifier <strong>%s</strong> doesn\'t exist and therefore cannot be removed',

                array( $identifier )
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

        if( ! isset( $this -> parameters -> {$identifier} ) ) {
            return NULL;
        }

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
     */
    public function __isset( $identifier ) {
        return $this -> offsetExists( $identifier );
    }

    /**
     * Removes a Parameter Identifier
     *
     * @param string $identifier
     *  Parameter Identifier
     *
     * @throws \Next\Components\Debug\Exception
     *  thrown if trying to remove a Identifier that doesn't exist
     */
    public function __unset( $identifier ) {
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
     */
    public function __set( $identifier, $value ) {
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
     */
    public function __get( $identifier ) {
        return $this -> offsetGet( $identifier );
    }
}
