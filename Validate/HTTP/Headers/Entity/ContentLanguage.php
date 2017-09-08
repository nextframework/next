<?php

/**
 * HTTP Entity Header Field Validator Class: Content-Language | Validate\Headers\Entity\ContentLanguage.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Entity;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class
use Next\Validate\ISO\ISO639;              # ISO 639 Validation Class
use Next\Validate\ISO\ISO3166;             # ISO 3166 Validation Class

/**
 * Content-Language Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentLanguage extends Object implements Headers {

    /**
     * Validates Content-Language Header Field in according to RFC 2616 Section 14.12
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Content-Language  = "Content-Language" ":" 1#language-tag
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.12
     *  RFC 2616 Section 14.12
     */
    public function validate() {

        /**
         * @internal
         * The rules are the same of Accept-Language, but here Quality Values
         * are not considerated
         */
        preg_match(

            '@^(?<abbr>[a-zA-Z]{2})(?:-(?<country>[a-zA-Z]{2}))?$@',

            $this -> options -> value, $match
        );

        if( count( $match ) != 0 ) {

            /**
             * @internal
             *
             * General Format is correct
             * Let's check chosen Language Abbreviation against ISO 639 Standards
             */
            $ISO = new ISO639(
                array( 'value' => ( array_key_exists( 'abbr', $match ) ? $match['abbr'] : NULL ) )
            );

            if( $ISO -> validate() ) {

                /**
                 * @internal
                 *
                 * Language Abbreviation is Valid
                 * Let's check chosen Country Code, if present, against ISO 3166 Standards
                 */
                if( isset( $match['country'] ) ) {

                    $ISO = new ISO3166( array( 'value' => $match['country'] ) );

                    if( $ISO -> validate() ) return TRUE;
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
     * Defines which Parameter Options are known by the Validator Class
     *
     * @return array
     *  An array with Custom Options overriding or complementing Object defaults
     */
    public function setOptions() {
        return [ 'value' => [ 'required' => TRUE ] ];
    }
}
