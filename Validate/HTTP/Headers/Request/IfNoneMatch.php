<?php

namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * If-None-Match Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class IfNoneMatch extends Object implements Headers {

    /**
     * Validates If-None-Match Header Field in according to RFC 2616 Section 14.26
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        If-None-Match = "If-None-Match" ":" ( "*" | 1#entity-tag )
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.26
     *  RFC 2616 Section 14.26
     */
    public function validate() {

        // We can't validate ETags yet, so...

        return TRUE;
    }
}
