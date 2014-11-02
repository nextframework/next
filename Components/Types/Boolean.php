<?php

namespace Next\Components\Types;

/**
 * Boolean Datatype Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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
        return ( gettype( $value ) == 'boolean' || is_bool( $value ) );
    }

    /**
     * Prototype resources to object
     *
     * @param mixed|optional $value
     *  An optional value to be used by prototyped resource
     */
    protected function prototype( $b = NULL ) {

        $this -> implement(

            'compare',

            function( $b2 ) use( $b ) {
                return ( (bool) $b === (bool) $b2 );
            }
        );
    }
}