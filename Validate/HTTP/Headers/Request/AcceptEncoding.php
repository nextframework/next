<?php

namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;            # HTTP Protocol Headers Interface
use Next\Components\Object;                        # Object Class
use Next\Validate\IANA\ContentEncoding as IANA;    # IANA Content-Encoding Validation Class

/**
 * Accept-Encoding Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class AcceptEncoding extends Object implements Headers {

    /**
     * Validates Accept-Charset Header Field in according to RFC 2616 Section 14.3
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Accept-Encoding  = "Accept-Encoding" ":"
     *                           1#( codings [ ";" "q" "=" qvalue ] )
     *
     *        codings          = ( content-coding | "*" )
     * </code>
     *
     * @param string $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.3
     *  RFC 2616 Section 14.3
     */
    public function validate( $data ) {

        preg_match(

            sprintf( '@^(?<encoding>\*|%s)(?:;q=(?<quality>%s))?$@x', IANA::ENCODING, self::FLOAT ),

            $data, $match
        );

        return ( $match != 0 );
    }
}
