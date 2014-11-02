<?php

namespace Next\Validate\HTTP\Headers\Entity;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Content-Length Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentLength extends Object implements Headers {

    /**
     * Validates Content-Length Header Field in according to RFC 2616 Section 14.13
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Content-Length    = "Content-Length" ":" 1*DIGIT
     * </code>
     *
     * @param string $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.13
     *  RFC 2616 Section 14.13
     */
    public function validate( $data ) {
        return ( preg_match( '/^[0-9]+$/', $data ) != 0 );
    }
}
