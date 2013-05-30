<?php

namespace Next\Validate\HTTP\Headers\Common;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Trailer Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Trailer extends Object implements Headers {

    /**
     * Validates Trailer Header Field in according to RFC 2616 Section 14.40
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Trailer  = "Trailer" ":" 1#field-name
     * </code>
     *
     * @param string $data
     *   Data to validate
     *
     * @return boolean
     *   TRUE if valid and FALSE otherwise
     *
     * @link
     *   http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.40
     *   RFC 2616 Section 14.40
     */
    public function validate( $data ) {

        // Insufficient information :(

        return TRUE;
    }
}
