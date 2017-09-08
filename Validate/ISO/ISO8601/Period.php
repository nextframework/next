<?php

/**
 * ISO-8601 Validator Class | Validate\ISO\ISO8601\Period.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
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

        preg_match( $regex, strval( $this -> options -> value ), $matches );

        // Shifting the required 'P'

        array_shift( $matches );

        return ( count( $matches ) == 0 ? FALSE : TRUE );
    }

    // Parameterizable Interface Method Overriding

    /**
     * Set Class Options.
     * Defines Parameter Options requirements rules
     *
     * @return array
     *  An array with Custom Options overriding or complementing Object defaults
     */
    public function setOptions() {
        return [ 'value' => [ 'required' => TRUE ] ];
    }
}
