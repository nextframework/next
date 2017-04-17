<?php

namespace Next\HTTP\Headers\Fields\Request;

use Next\HTTP\Headers\Fields\Request;        # Request Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;  # Header Field Abstract Class

/**
 * Accept-Language Header Field Class
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
     * @return Next\Validate\Validate
     *  Associated Validator
     */
    protected function getValidator( $value ) {
        return new \Next\Validate\HTTP\Headers\Request\AcceptLanguage(
            array( 'value' => $value )
        );
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
