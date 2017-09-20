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

use Next\Components\Interfaces\Verifiable;      # Verifiable Interface
use Next\Components\Object;                     # Object Class

/**
 * Defines the base structure for a Data-type
 *
 * @package    Next\Components\Types
 */
abstract class AbstractTypes extends Object implements Verifiable, Type {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => FALSE ]
    ];

    /**
     * Type Value
     *
     * @var mixed $_value
     */
    protected $_value;

    /**
     * Additional Initialization.
     * Sets the data-type value and prototypes resource to it
     */
    public function init() {

        $value = ( $this -> options -> value instanceof Type ?
                    $this -> options -> value -> get() : $this -> options -> value );

        $this -> _value = $value;

        // Prototyping

        $this -> prototype();
    }

    // Accessory Method

    /**
     * Get value
     *
     * @return mixed
     *  Object value
     */
    public function get() {
        return $this -> _value;
    }

    // Overloading

    /**
     * Return the \Next\Components\Types\Type value, regardless
     * the desired property name
     *
     * This allows the Object value to be read without invoking the
     * accessory method which comes in handy with strict line length
     * standards
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