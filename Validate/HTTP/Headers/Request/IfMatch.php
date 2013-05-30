<?php

namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * If-Match Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class IfMatch extends Object implements Headers {

    /**
     * Validates If-Match Header Field in according to RFC 2616 Section 14.24
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        If-Match = "If-Match" ":" ( "*" | 1#entity-tag )
     * </code>
     *
     * @param string $data
     *   Data to validate
     *
     * @return boolean
     *   TRUE if valid and FALSE otherwise
     *
     * @link
     *   http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.24
     *   RFC 2616 Section 14.24
     */
    public function validate( $data ) {

        // We can't validate ETags yet, so...

        return TRUE;
    }
}
