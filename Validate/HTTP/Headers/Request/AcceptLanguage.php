<?php

/**
 * HTTP Request Header Field Validator Class: Accept-Language | Validate\Headers\Request\AcceptLanguage.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class
use Next\Validate\ISO\ISO639;              # ISO 639 Validation Class
use Next\Validate\ISO\ISO3166;             # ISO 3166 Validation Class

/**
 * Accept-Language Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class AcceptLanguage extends Object implements Headers {

    /**
     * Validates Accept-Language Header Field in according to RFC 2616 Section 14.3
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Accept-Language = "Accept-Language" ":"
     *                          1#( language-range [ ";" "q" "=" qvalue ] )
     *
     *        language-range  = ( ( 1*8ALPHA *( "-" 1*8ALPHA ) ) | "*" )
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
     *  RFC 2616 Section 14.4
     */
    public function validate() {

        preg_match(

            sprintf(
                '@^(?<abbr>[a-zA-Z]{2})(?:-(?<country>[a-zA-Z]{2}))?(?:;q=(?<quality>%s))?$@x', self::FLOAT
            ),

            $this -> options -> value, $match
        );

        if( count( $match ) != 0 ) {

            /**
             * @internal
             * General Format is correct
             * Let's check chosen Language Abbreviation against ISO 639 Standards
             */
            $ISO = new ISO639( [ 'value' => $match['abbr'] ] );

            if( $ISO -> validate() ) {

                /**
                 * @internal
                 * Language Abbreviation is Valid
                 * Let's check chosen Country Code, if present, against ISO 3166 Standards
                 */
                if( isset( $match['country'] ) ) {

                    $ISO = new ISO3166( [ 'value' => $match['country'] ] );

                    if( $ISO -> validate() !== FALSE ) return TRUE;
                }

                // Valid, but without Country Code

                return TRUE;
            }

            // Invalid Language Abbreviation

            return FALSE;
        }

        // Invalid everything xD

        return FALSE;
    }

    // Parameterizable Interface Method Overriding

    /**
     * Set Class Options.
     * Defines Parameter Options requirements rules
     *
     * @return array
     *  An array with Custom Options overriding or complementing Object defaults
     */
    public function setOptions() {
        return [ 'value' => [ 'required' => TRUE ] ];
    }
}
