<?php

/**
 * HTTP Common Header Field Class: Cache-Control | HTTP\Headers\Common\Cache-Control.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Common;

use Next\Validation\Validator as Validators;    # Validators Interface
use Next\HTTP\Headers\Field;                    # Header Field Abstract Class

/**
 * Common Header Field Validation Class: 'Cache Control'
 */
use Next\Validation\HTTP\Headers\Common\CacheControl as Validator;

/**
 * Common Header Field: 'Cache-Control'
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Validation\Validator
 *             Next\HTTP\Headers\Field
 *             Next\Validation\HTTP\Headers\CacheControl
 */
class CacheControl extends Field {

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
        return [ 'name' => 'Cache-Control', 'acceptMultiples' => TRUE ];
    }
}
