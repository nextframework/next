<?php

/**
 * Types Component Interface | Components\Types\Type.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Types;

use Next\Components\Interfaces\Prototypable;    # Prototypable Resource Class

/**
 * Defines the Data-type Type, with all methods that must be present
 * in an Data-type, be it through \Next\Components\Types\AbstractTypes
 * or the concrete implementations of it
 *
 * @package    Next\Components\Types
 */
interface Type extends Prototypable {

    /**
     * Set value
     *
     * @param mixed $value
     *  Value to set
     */
    public function set( $value );

    /**
     * Get value
     */
    public function get();
}