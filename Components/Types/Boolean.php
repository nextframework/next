<?php

/**
 * Types Component "Boolean" Type Class | Components\Types\Boolean.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Types;

/**
 * Defines the Boolean Data-type Type and prototypes some o PHP Boolean
 * functions (some in different argument order) and some external/custom
 * resources as well
 *
 * @package    Next\Components\Types
 */
final class Boolean extends AbstractTypes {

    // Abstract Methods Implementation

    /**
     * Check whether or not given value is acceptable by datatype class
     *
     * @param mixed $value
     *  Value to set
     *
     * @return boolean
     *  TRUE if given value is of the type boolean or given value
     *  is any other accepted boolean variants.
     *
     *  Return FALSE otherwise
     */
    protected function accept( $value ) {
        return is_bool( $value );
    }

    /**
     * Prototype resources to object
     *
     * @return void
     */
    protected function prototype() {

        // Prototypes that requires a value to work with

        if( $this -> _value !== NULL ) {

            // Custom Functions

            $value = $this -> _value;

            $this -> implement(

                'compare',

                function( $b ) use( $value ) {
                    return ( (bool) $value === (bool) $b );
                }
            );
        }
    }
}