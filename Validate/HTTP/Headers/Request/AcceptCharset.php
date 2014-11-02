<?php

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
     * <code>
     *        Accept-Charset = "Accept-Charset" ":"
     *           1#( ( charset | "*" )[ ";" "q" "=" qvalue ] )
     *
     *        If Quality Value is not present, then 1 is assumed
     * </code>
     *
     * @param string $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.2
     *  RFC 2616 Section 14.2
     */
    public function validate( $data ) {

        preg_match(

            sprintf( '@^(?<charset>%s)(?:;q=(?<quality>%s))?$@x', IANA::CHARSET, self::FLOAT ),

            $data, $match
        );

        if( count( $match ) != 0 ) {

            /**
             * \internal
             * General Format is correct
             * Let's check chosen charset against IANA's Charset Registry
             */

            // Validating against IANA's Registry Common Names and its Aliases

            $IANA = new IANA;

            if( $IANA -> validate( $match['charset'] ) ) {

                return TRUE;
            }
        }

        return FALSE;
    }
}
