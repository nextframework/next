<?php

namespace Next\Validate\HTTP\Headers\Response;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * RFC 2616 WWW-Authenticate Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class WWWAuthenticate extends Object implements Headers {

    /**
     * Validates WWW-Authenticate Header Field in according to RFC 2616 Section 14.47
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        WWW-Authenticate  = "WWW-Authenticate" ":" 1#challenge
     * </code>
     *
     * @param string $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.47
     *  RFC 2616 Section 14.47
     */
    public function validate( $data ) {
        return TRUE;
    }
}
