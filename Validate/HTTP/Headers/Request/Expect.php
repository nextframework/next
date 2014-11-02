<?php

namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Expect Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Expect extends Object implements Headers {

    /**
     * Validates Expect Header Field in according to RFC 2616 Section 14.20
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *
     *        Expect       =  "Expect" ":" 1#expectation
     *
     *        expectation  =  "100-continue" | expectation-extension
     *        expectation-extension =  token [ "=" ( token | quoted-string )
     *                                 *expect-params ]
     *
     *        expect-params =  ";" token [ "=" ( token | quoted-string ) ]
     * </code>
     *
     * @param string $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.20
     *  RFC 2616 Section 14.20
     */
    public function validate( $data ) {

        // Insufficient information

        return TRUE;
    }
}
