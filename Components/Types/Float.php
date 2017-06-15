<?php

/**
 * Types Component "Float" Type Class | Components\Types\Float.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Types;

/**
 * Defines the Float Data-type Type
 *
 * @package    Next\Components\Types
 */
final class Float extends Number {

    // Abstract Methods Implementation

    /**
     * Check whether or not given value is acceptable by datatype class
     *
     * @param mixed $value
     *  Value to set
     *
     * @return boolean
     *  TRUE if given value is of the type integer and FALSE otherwise
     */
    protected function accept( $value ) {
        return ( is_float( $value ) && ! is_int( $value ) );
    }
}