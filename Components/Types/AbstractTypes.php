<?php

namespace Next\Components\Types;

use Next\Components\Object;    # Object Class

/**
 * Datatypes Abstract Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractTypes extends Object implements Type {

    /**
     * Type Value
     *
     * @var mixed $value
     */
    protected $value;

    /**
     * Datatype Constructor
     *
     * @param mixed|optional $value
     *   Value to set
     *
     * @throws InvalidArgumentException
     *   Given argument ois not acceptable by concrete datatype class
     */
    public function __construct( $value = NULL ) {

        parent::__construct();

        // If a value was defined...

        if( ! is_null( $value ) ) {

            // ... let's cCheck its acceptance before set

            if( $this -> accept( $value ) === FALSE ) {

                throw new \InvalidArgumentException(

                    'Argument is not a ' . $this
                );
            }
        }

        // Prototyping (even without value)...

        $this -> prototype( $value );

        $this -> value =& $value;
    }

    // Accessors

    /**
     * Set value
     *
     * @param mixed $value
     *   Value to set
     *
     * @return Next\Components\Interfaces\Type
     *   A new object with new value
     */
    public function set( $value ) {
        return new static( $value );
    }

    /**
     * Get value
     *
     * @return mixed
     *   Object value
     */
    public function get() {
        return $this -> value;
    }

    // Abstract Methods Definition

    /**
     * Check whether or not given value is acceptable by datatype class
     *
     * @param mixed $value
     *   Value to set
     */
    abstract protected function accept( $value );

    /**
     * Prototype resources to object
     *
     * @param mixed|optional $value
     *   An optional value to be used by prototyped resource
     */
    abstract protected function prototype( $value = NULL );
}