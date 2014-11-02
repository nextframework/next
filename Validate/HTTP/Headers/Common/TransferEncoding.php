<?php

namespace Next\Validate\HTTP\Headers\Common;

use Next\Validate\HTTP\Headers\Headers;            # HTTP Protocol Headers Interface
use Next\Components\Object;                        # Object Class
use Next\Validate\IANA\ContentEncoding as IANA;    # IANA Content-Encoding Validation Class

/**
 * Tranfer-Encoding Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class TransferEncoding extends Object implements Headers {

    /**
     * Validates Transfer-Encoding Header Field in according to RFC 2616 Section 14.41
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Transfer-Encoding       = "Transfer-Encoding" ":" 1#transfer-coding
     * </code>
     *
     * @param string $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.41
     *  RFC 2616 Section 14.41
     */
    public function validate( $data ) {
        return ( preg_match( sprintf( '@^(chunked|%s)$@i', IANA::ENCODING ), $data ) != 0 );
    }
}
