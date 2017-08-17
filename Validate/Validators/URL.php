<?php

/**
 * URL Validator Class | Validate\Validators\URL.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\Validators;

use Next\Validate\Validator;    # Validator Interface
use Next\Components\Object;     # Object Class

/**
 * URL Validator Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class URL extends Object implements Validator {

    // Validator Interface Interface Methods

    /**
     * Validates given URL
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        $value = $this -> options -> value;

        if( ! is_string( $value ) ) {

            $this -> _error = vsprintf(

                'Validator <strong>%s</strong> expects a string, %s given',

                [
                  $this -> getClass() -> getNamespaceName(), gettype( $value )
                ]
            );

            return FALSE;
        }

        return ( filter_var( $value, \FILTER_VALIDATE_URL ) !== FALSE );
    }
}
