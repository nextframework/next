<?php

/**
 * HTTP Request Header Field Validator Class: Accept | Validate\Headers\Request\Accept.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class
use Next\Validate\IANA\MIME as IANA;       # IANA MIME-Type Validation Class

/**
 * Accept Header Validation Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Accept extends Object implements Headers {

    /**
     * Validates Accept Header Field in according to RFC 2616 Section 14.1
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Accept            = "Accept" ":"
     *                            #( media-range [ accept-params ] )
     *
     *        media-range       = ( "*"/"*"
     *                            | ( type "/" "*" )
     *                            | ( type "/" subtype )
     *                            ) *( ";" parameter )
     *
     *        accept-params     = ";" "q" "=" qvalue *( accept-extension )
     *
     *        accept-extension  = ";" token [ "=" ( token | quoted-string ) ]
     * </code>
     *
     * There is a little difference in the first "Media Range" possibility in order
     * to make it compatible with Doc Comment Syntax
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.1
     *  RFC 2616 Section 14.1
     */
    public function validate() {

        preg_match(

            sprintf(

                /**
                 * @internal
                 *
                 * Range followed optionally by:
                 *
                 * ; (semi-colon) with none, one or more spaces followed by:
                 *
                 *          q=\<quality_value\> -OR-
                 *          none, one or more key=value pairs, defined as tokens
                 *
                 * There are no backslashes in RFC 2616 Section 14.1
                 * about Quality Value
                 *
                 * This character is only used as escape character
                 */
                '@^(?<range>%s)(?:;\s*(?:q=(?<quality>%s))|(?<tokens>%s=(?:[\'"]?%s[\'"]?);?)*?)?$@x',

                IANA::RANGE, self::FLOAT, self::TOKEN, self::TOKEN
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
