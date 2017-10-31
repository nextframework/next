<?php

/**
 * HTTP Entity Header Field Class: Allow | HTTP\Headers\Entity\Allow.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Entity;

use Next\Validation\Validator as Validators;    # Validators Interface
use Next\HTTP\Headers\Field;                    # Header Field Abstract Class

/**
 * Entity Header Field Validation Class: 'Allow'
 */
use Next\Validation\HTTP\Headers\Entity\Allow as Validator;

/**
 * Entity Header Field: 'Allow'
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Validation\Validator
 *             Next\HTTP\Headers\Field
 *             Next\Validation\HTTP\Headers\Allow
 */
class Allow extends Field {

    // Methods Overwritten

    /**
     * POST Check Routines
     *
     * Allowed methods should be uppercased
     *
     * @param string $data
     *  Data to manipulate after validation
     *
     * @return string Validated Data
     */
    protected function postCheck( $data ) : string {
        return strtoupper( $data );
    }

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
        return [ 'name' => 'Allow', 'acceptMultiples' => TRUE ];
    }
}
