<?php

/**
 * HTTP Request Header Field Class: Accept-Language | HTTP\Headers\Fields\Request\AcceptLanguage.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Fields\Request;

use Next\HTTP\Headers\Fields\Request;          # Request Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;    # Header Field Abstract Class

/**
 * Request 'Accept-Language' Header field Validator Class
 */
use Next\Validate\HTTP\Headers\Request\AcceptLanguage as Validator;

/**
 * Request 'Accept-Language' Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class AcceptLanguage extends AbstractField implements Request {

    // Methods Overwritten

    /**
     * POST Check Routines
     *
     * Fix Language Abbreviation and/or Country Code cases
     *
     * @param string $data
     *  Data to manipulate after validation
     *
     * @return string Validated Data
     */
    protected function postCheck( $data ) {

        return preg_replace_callback(

            '@^(.*?)(-.*?)?(;q=.*?)?$@',

            function( array $matches ) {

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
     * @return \Next\Validate\Validator
     *  Associated Validator
     */
    protected function getValidator( $value ) {
        return new Validator( array( 'value' => $value ) );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'Accept-Language', 'acceptMultiples' => TRUE );
    }
}
