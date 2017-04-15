<?php

namespace Next\Components;

use Next\Components\Debug\Exception;      # Exception Class

/**
 * Parameter Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Parameter implements \Countable, \ArrayAccess {

    /**
     * Object Parameters
     *
     * @var stdClass|Next\Components\Parameter $parameters
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

        if( $common instanceof Parameter ) {

            $this -> parameters = $common;

        } else {

            $this -> parameters = new \stdClass;

            // Building Options (by merging them)

            if( ! is_null( $common ) ) $this -> merge( $common );

            if( ! is_null( $custom ) ) $this -> merge( $custom );

            if( ! is_null( $user ) ) $this -> merge( $user );
        }
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
     *       <li>Next\Components\Object object</li>
     *
     *   </ul>
     *
     * @throws Next\Components\Debug\Exception
     *  Any option is not an associative array, because
     *  doesn't make sense to have a public property just to store NULL
     */
    public function merge( $param ) {

        // Converting, if needed

        if( is_array( $param ) ) $param = Object::map( $param );

        if( $param instanceof Object || $param instanceof \stdClass ) {

            // Merging...

            foreach( $param as $property => $value ) {

                if( is_int( $property ) ) {

                    throw new Exception(

                        'All options must be an associative array'
                    );

                } else {

                    $this -> parameters -> {$property} = $value;
                }
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
    public function count() {
        return count( get_object_vars( $this ), TRUE );
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
     * @throws Next\Components\Debug\Exception
     *  Thrown if trying to add a value without an identifier for it, differently of
     *  most implementations of ArrayAccess::offsetSet():
     */
    public function offsetSet( $identifier, $value ) {

        if( is_null( $identifier ) ) {

            throw new Exception(
                'Parameter Objects require a string to serve as identifier for the value being added'
            );
        }

        $this -> parameters -> {$identifier} = $value;
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
        return isset( $this -> parameters -> {$identifier} );
    }

    /**
     * Removes a Parameter Identifier
     *
     * @param string $identifier
     *  Parameter Identifier
     *
     * @throws Next\Components\Debug\Exception
     *  thrown if trying to remove a Identifier that doesn't exist
     */
    public function offsetUnset($identifier) {

        if( ! isset( $this -> parameters -> {$identifier} ) ) {

            throw new Exception(

                sprintf(
                    'Identifier <strong>%s</strong> doesn\'t exist and thus cannot be removed', $identifier
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
        return ( isset( $this -> parameters -> {$identifier} ) ? $this -> parameters -> {$identifier} : NULL );
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
        return ( isset( $this -> parameters -> {$identifier} ) );
    }

    /**
     * Removes a Parameter Identifier
     *
     * @param string $identifier
     *  Parameter Identifier
     *
     * @throws Next\Components\Debug\Exception
     *  thrown if trying to remove a Identifier that doesn't exist
     */
    public function __unset( $identifier ) {

        if( ! isset( $this -> parameters -> {$identifier} ) ) {

            throw new Exception(

                sprintf(
                    'Identifier <strong>%s</strong> doesn\'t exist and thus cannot be removed', $identifier
                )
            );
        }

        unset( $this -> parameters -> {$identifier} );
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
        $this -> parameters -> {$identifier} = $value;
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
        return ( isset( $this -> parameters -> {$identifier} ) ? $this -> parameters -> {$identifier} : NULL );
    }
}
