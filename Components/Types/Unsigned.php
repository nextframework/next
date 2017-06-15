<?php

/**
 * Types Component "Unsigned" Type Class | Components\Types\Unsigned.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Types;

/**
 * Defines the Unsigned Data-type Type
 *
 * @package    Next\Components\Types
 */
final class Unsigned extends Number {

    // Abstract Methods Implementation

    /**
     * Check whether or not given value is acceptable by data-type class
     *
     * The Integer data-type accepts all POSITIVE non-float numbers and the zero
     *
     * @param mixed $value
     *  Value to set
     *
     * @return boolean
     *  TRUE if given value is of the type integer and FALSE otherwise
     *
     * @see \Next\Components\Types\Number::accept()
     * @see \Next\Components\Types\Integer::accept()
     * @see \Next\Components\Types\Float::accept()
     */
    protected function accept( $value ) {
        return parent::accept( $value ) && ( $value >= 0 );
    }
}