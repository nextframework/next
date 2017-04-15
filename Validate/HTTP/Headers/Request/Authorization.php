<?php

namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Authorization Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Authorization extends Object implements Headers {

    /**
     * Validates Authorization Header Field in according to RFC 2616 Section 14.8
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Authorization  = "Authorization" ":" credentials
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.8
     *  RFC 2616 Section 14.8
     */
    public function validate() {
        return TRUE;
    }
}
