<?php

/**
 * Types Components Abstract Class | Components\Types\AbstractTypes.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Types;

use Next\Components\Object;    # Object Class

/**
 * Defines the base structure for a Data-type
 *
 * @package    Next\Components\Types
 */
abstract class AbstractTypes extends Object implements Type {

    /**
     * Type Value
     *
     * @var mixed $_value
     */
    protected $_value;

    /**
     * Datatype Constructor
     *
     * @param mixed|\Next\Components\Types\Type|optional $value
     *  Value to build the Type object, be it raw or another Type Object
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for each Type Object
     *
     * @throws InvalidArgumentException
     *  Given argument is not acceptable by concrete datatype class
     *
     * @see \Next\Components\Types\AbstractTypes::set()
     */
    public function __construct( $value = NULL, $options = NULL ) {

        parent::__construct( $options );

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
     * @return \Next\Components\Interfaces\Type
     *  Type Object (Fluent-Interface)
     *
     * @throws InvalidArgumentException
     *  Given argument is not acceptable by concrete datatype class
     */
    public function set( $value ) {

        if( $this -> accept( $value ) === FALSE ) {

            throw new \Next\Debug\Exception\Exception(

                sprintf(

                    'Argument <strong>%s</strong> is not a valid <em>%s</em>',

                    ( $value !== NULL ? $value : 'NULL' ), $this -> getClass() -> getName()
                )
            );
        }

        $this -> _value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return mixed
     *  Object value
     */
    public function get() {
        return $this -> _value;
    }

    // Abstract Methods Definition

    /**
     * Check whether or not given value is acceptable by datatype class
     *
     * @param mixed $value
     *  Value to set
     */
    abstract protected function accept( $value );

    // Overloading

    /**
     * Return the \Next\Components\Types\Type value, regardless
     * the desired property name
     *
     * This allows the Object value to be read without invoking the accessor
     * method -AND- through any character length, which comes in handy with
     * strict line length standards
     *
     * @param mixed|string $property
     *  Property to be retrieved. Not used!
     *
     * @return mixed
     *  Object value
     */
    public function __get( $property ) {
        return $this -> _value;
    }
}