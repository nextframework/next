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
     * @param mixed|Next\Components\Types\Type $value
     *  Value to build the Type object, be it raw or another Type Object
     *
     * @throws InvalidArgumentException
     *  Given argument is not acceptable by concrete datatype class
     *
     * @see Next\Components\Types\AbstractTypes::set()
     */
    public function __construct( $value ) {

        parent::__construct();

        $this -> set(
            ( $value instanceof Type ? $value -> get() : $value )
        );

        // Prototyping

        $this -> prototype();
    }

    // Accessors

    /**
     * Set value
     *
     * @param mixed $value
     *  Value to set
     *
     * @return Next\Components\Interfaces\Type
     *  Type Object (Fluent-Interface)
     *
     * @throws InvalidArgumentException
     *  Given argument is not acceptable by concrete datatype class
     */
    public function set( $value ) {

        if( $this -> accept( $value ) === FALSE ) {

            throw new \InvalidArgumentException(

                'Argument is not a ' . $this
            );
        }

        $this -> value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return mixed
     *  Object value
     */
    public function get() {
        return $this -> value;
    }

    // Abstract Methods Definition

    /**
     * Check whether or not given value is acceptable by datatype class
     *
     * @param mixed $value
     *  Value to set
     */
    abstract protected function accept( $value );

    /**
     * Prototype resources to object
     */
    abstract protected function prototype();
}