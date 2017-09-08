<?php

/**
 * HTTP Request Header Field Validator Class: Accept-Charset | Validate\Headers\Request\AcceptCharset.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class
use Next\Validate\IANA\Charset as IANA;    # IANA Charset Validation Class

/**
 * Accept-Charset Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class AcceptCharset extends Object implements Headers {

    /**
     * Validates Accept-Charset Header Field in according to RFC 2616 Section 14.2
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Accept-Charset = "Accept-Charset" ":"
     *           1#( ( charset | "*" )[ ";" "q" "=" qvalue ] )
     *
     *        If Quality Value is not present, then 1 is assumed
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.2
     *  RFC 2616 Section 14.2
     */
    public function validate() {

        preg_match(

            sprintf( '@^(?<charset>%s)(?:;q=(?<quality>%s))?$@x', IANA::CHARSET, self::FLOAT ),

            $this -> options -> value, $match
        );

        if( count( $match ) != 0 ) {

            /**
             * \internal
             * General Format is correct
             * Let's check chosen charset against IANA's Charset Registry
             */

            // Validating against IANA's Registry Common Names and its Aliases

            $IANA = new IANA(
                [ 'value' => ( array_key_exists( 'charset', $match ) ? $match['charset'] : NULL ) ]
            );

            if( $IANA -> validate() !== FALSE ) return TRUE;
        }

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
