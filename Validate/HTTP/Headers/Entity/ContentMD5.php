<?php

namespace Next\Validate\HTTP\Headers\Entity;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Content-MD5 Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentMD5 extends Object implements Headers {

    /**
     * Validates Content-MD5 Header Field in according to RFC 2616 Section 14.15
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Content-MD5   = "Content-MD5" ":" md5-digest
     *        md5-digest   = <base64 of 128 bit MD5 digest as per RFC 1864>
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.15
     *  RFC 2616 Section 14.15
     */
    public function validate() {

        preg_match( '/^(?<hash>[a-zA-Z0-9\+\=\/]+)$/', $this -> options -> value, $match );

        return ( count( $match ) != 0 && strlen( base64_decode( $match['hash'] ) ) == 32 );
    }
}
