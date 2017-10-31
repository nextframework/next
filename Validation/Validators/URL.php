<?php

/**
 * URL Validator Class | Validation\Validators\URL.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\Validators;

use Next\Validation\Validator;    # Validator Interface
use Next\Components\Object;       # Object Class

/**
 * The URL Validator checks if input string is a valid URL
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\Validator
 *             Next\Components\Object
 */
class URL extends Object implements Validator {

    // Validator Interface Interface Methods

    /**
     * Validates given URL
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() : bool {

        $value = $this -> options -> value;

        if( ! is_string( $value ) ) return FALSE;

        return ( filter_var( $value, \FILTER_VALIDATE_URL ) !== FALSE );
    }
}
