<?php

namespace Next\Validate\HTTP\Headers\Common;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Pragma Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Pragma extends Object implements Headers {

    /**
     * Validates Pragma Header Field in according to RFC 2616 Section 14.32
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Pragma            = "Pragma" ":" 1#pragma-directive
     *
     *        pragma-directive  = "no-cache" | extension-pragma
     *
     *        extension-pragma  = token [ "=" ( token | quoted-string ) ]
     * </code>
     *
     * @param string $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.32
     *  RFC 2616 Section 14.32
     */
    public function validate( $data ) {
        return preg_match( sprintf( '/^(no-cache|%s=[\'"]?%s[\'"]?)$/', self::TOKEN, self::TOKEN ), $data ) != 0;
    }
}
