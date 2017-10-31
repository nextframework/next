<?php

/**
 * HTTP Common Header Field Class: Warning | HTTP\Headers\Common\Warning.php
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
 * Common Header Field Validation Class: 'Warning'
 */
use Next\Validation\HTTP\Headers\Common\Warning as Validator;

/**
 * Common Header Field: 'Warning'
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Validation\Validator
 *             Next\HTTP\Headers\Field
 *             Next\Validation\HTTP\Headers\Warning
 */
class Warning extends Field {

    // Methods Overwritten

    /**
     * PRE Check Routines
     *
     * Distinguishing Warnng Message from Warning Date
     *
     * @param string $data
     *  Data to manipulate before validation
     *
     * @return string Data to Validate
     */
    protected function preCheck( $data ) : string {

        /**
         * @internal
         * Warning Date and Warning Message are too generic at same time they both
         * are optional, so let's give some help to our validator with something
         * to distinguish them
         */
        return preg_replace( '/\s*(Mon|Tue|Wed|Thu|Fri|Sat|Sun)/', '#\\1', $data );
    }

    /**
     * POST Check Routines
     *
     * Removing distinguishing character
     *
     * @param string $data
     *  Data to manipulate after validation
     *
     * @return string Validated Data
     */
    protected function postCheck( $data ) : string {
        return preg_replace( '/#(\w{3}),/', ' \\1,', $data );
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
        return [ 'name' => 'Warning', 'preserveWhitespace' => TRUE ];
    }
}
