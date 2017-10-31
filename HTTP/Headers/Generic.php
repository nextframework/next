<?php

/**
 * HTTP Generic Header Field Class | HTTP\Headers\Generic.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers;

use Next\Validation\Validator as Validators;    # Validators Interface

/**
 * Generic Header Field Validation Class
 */
use Next\Validation\HTTP\Headers\Generic as Validator;

/**
 * The Generic Header Field represents Headers unofficial Headers or Headers
 * that are unrecognised by Next Framework
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Validation\Validator
 *             Next\HTTP\Headers\Field
 *             Next\Validation\HTTP\Headers\Generic
 */
class Generic extends Field {

    // Abstract Methods Implementation

    /**
     * Get Header Field Validator
     *
     * @param mixed|string $value
     *  Header value to be validated
     *
     * @return \Next\Validation\Validator
     *  Associated Validator
     */
    protected function getValidator( $value ) : Validators {
        return new Validator( [ 'value' => $value ] );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() : array {
        return [ 'name' => 'Generic', 'acceptMultiples' => TRUE, 'preserveWhitespace' => TRUE ];
    }
}
