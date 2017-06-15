<?php

namespace Next\Validate\ISO\ISO8601;

use Next\Validate\Validator;    # Validator Interface

use Next\Components\Object;     # Object Class

/**
 * ISO-8601 Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Period extends Object implements Validator {

    // Validator Interface Method Implementation

    /**
     * Validates a ISO 8601 Duration Format
     *
     * @return boolean
     *  TRUE if given value is a valid ISO 8601 Period and FALSE otherwise
     *
     * @see http://en.wikipedia.org/wiki/Iso8601#Durations
     */
    public function validate() {

        $regex = '/P(([1-9][0-9]+[YMDW])+)?(T([1-9][0-9]+[HMS])+)?/';

        preg_match( $regex, strval( $$this -> options -> value ), $matches );

        // Shifting the required 'P'

        array_shift( $matches );

        return ( count( $matches ) == 0 ? FALSE : TRUE );
    }
}
