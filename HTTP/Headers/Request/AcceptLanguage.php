<?php

/**
 * HTTP Request Header Field Class: Accept-Language | HTTP\Headers\Request\AcceptLanguage.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Request;

use Next\Validation\Validator as Validators;    # Validators Interface
use Next\HTTP\Headers\Field;                    # Header Field Abstract Class

/**
 * Request Header Field Validation Class: 'Accept-Language'
 */
use Next\Validation\HTTP\Headers\Request\AcceptLanguage as Validator;

/**
 * Request Header Field: 'Accept-Language'
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Validation\Validator
 *             Next\HTTP\Headers\Field
 *             Next\Validation\HTTP\Headers\AcceptLanguage
 */
class AcceptLanguage extends Field {

    // Methods Overwritten

    /**
     * POST Check Routines
     *
     * Fix Language Abbreviation and/or Country Code cases
     *
     * @param string $data
     *  Data to manipulate after validation
     *
     * @return string
     *  Validated Data
     */
    protected function postCheck( $data ) : string {

        return preg_replace_callback(

            '@^(.*?)(-.*?)?(;q=.*?)?$@',

            function( array $matches ) : string {

                // Language Abbreviations must be lowercase

                $return = strtolower( $matches[ 1 ] );

                // Country Codes must be upercase

                if( isset( $matches[ 2 ] ) && strpos( $matches[ 2 ], 'q=' ) === FALSE ) {

                    $return .= strtoupper( $matches[ 2 ] );
                }

                // Quality Values

                if( isset( $matches[ 3 ] ) ) {

                    $return .= $matches[ 3 ]; // Untouched
                }

                return $return;
            },

            $data
       );
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
        return [ 'name' => 'Accept-Language', 'acceptMultiples' => TRUE ];
    }
}
