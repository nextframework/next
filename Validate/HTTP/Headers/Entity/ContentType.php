<?php

namespace Next\Validate\HTTP\Headers\Entity;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class
use Next\Validate\IANA\MIME as IANA;       # IANA MIME-Type Validation Class

/**
 * Content-Type Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentType extends Object implements Headers {

    /**
     * Validates Content-Type Header Field in according to RFC 2616 Section 14.17
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Content-Type   = "Content-Type" ":" media-type
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17
     *  RFC 2616 Section 14.17
     */
    public function validate() {

        /**
         * @internal
         * The validations Rules are the same of Accept Validator
         * except this one does not considerate multiple values
         * nor Quality Values, but may accept some tokens
         */
        preg_match(

            sprintf(

                '@^(?<range>%s)(?:;\s*(?<tokens>%s=[\'"]?%s[\'"]?;?)*?)?$@x',

                IANA::RANGE, self::TOKEN, self::TOKEN
            ),

            $this -> options -> value, $match
        );

        /**
         * @internal
         * array_filter() is being used in order to ignore
         * some groups / subgroups without values
         */
        $match = array_filter( $match );

        if( count( $match ) != 0 ) {

            /**
             * @internal
             * General Format is correct
             * Let's check chosen charset against IANA's MIME Types Registry
             *
             * Only Full MIME's will be analyzed. E.g.: text/html
             * Partial MIME's, like "text/*" (without quotes), don't, being automatically accepted
             */
            if( preg_match( sprintf( '@^%s$@', IANA::MIME ), $match['range'], $mime ) != 0 ) {

                $IANA = new IANA( array( 'value' => $mime[ 0 ] ) );

                if( $IANA -> validate() ) {

                    // Valid as Full MIME

                    return TRUE;
                }

                // Invalid as Full MIME

                return FALSE;
            }

            // Valid as Partial MIME

            return TRUE;
        }

        // Invalid everything xD

        return FALSE;
    }
}
