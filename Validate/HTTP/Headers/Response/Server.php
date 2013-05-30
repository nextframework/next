<?php

namespace Next\Validate\HTTP\Headers\Response;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * RFC 2616 Server Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Server extends Object implements Headers {

    /**
     * Validates Server Header Field in according to RFC 2616 Section 14.38
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Server = "Server" ":" 1*( product | comment )
     * </code>
     *
     * @param string $data
     *   Data to validate
     *
     * @return boolean
     *   TRUE if valid and FALSE otherwise
     *
     * @link
     *   http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.38
     *   RFC 2616 Section 14.38
     */
    public function validate( $data ) {

        // Impossible to validate due uncertain number of different servers around the world

        return TRUE;
    }
}
