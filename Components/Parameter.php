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
class Parameter implements \Countable {

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

                    throw new \Next\Components\Debug\Exception(

                        'All options must be an associative array'
                    );

                } else {

                    $this -> parameters -> {$property} = $value;
                }
            }
        }
    }

    // Interface Methods Implementation

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

    // Overloading

    /**
     * Grant access to Parameter properties
     *
     * @param  string $property
     *  Parameter property
     *
     * @return mixed
     *  Parameter value
     */
    public function __get( $property ) {
        return $this -> parameters -> {$property};
    }

    /**
     * Check if a Parameter property exist
     *
     * @param  string  $property
     *  Parameter property
     *
     * @return boolean
     *  TRUE if Parameter property exist and FALSE otherwise
     */
    public function __isset( $property ) {
        return ( isset( $this -> parameters -> {$property} ) );
    }
}
