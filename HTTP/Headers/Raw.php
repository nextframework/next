<?php

/**
 * HTTP Raw Header Field Class | HTTP\Headers\Raw.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers;

use Next\Validation\Validator as Validators;    # Validators Interface

/**
 * Raw Header Field Validation Class
 */
use Next\Validation\HTTP\Headers\Raw as Validator;

/**
 * The Raw Header Field represents Headers that needs to be manually sent or
 * Headers that doesn't have a name, like HTTP Status Codes
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Validation\Validator
 *             Next\HTTP\Headers\Field
 *             Next\Validation\HTTP\Headers\Raw
 */
class Raw extends Field {

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
        return [ 'name' => 'Raw', 'acceptMultiples' => FALSE, 'preserveWhitespace' => TRUE ];
    }
}
