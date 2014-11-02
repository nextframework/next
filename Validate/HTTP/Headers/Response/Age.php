<?php

namespace Next\Validate\HTTP\Headers\Response;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * RFC 2616 Age Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Age extends Object implements Headers {

    /**
     * Validates AAge Header Field in according to RFC 2616 Section 14.6
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Age = "Age" ":" age-value
     *
     *        age-value = delta-seconds
     * </code>
     *
     * @param string $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.6
     *  RFC 2616 Section 14.6
     */
    public function validate( $data ) {

        // Age Header value must be a positive integer representing the seconds

        return ( preg_match( '@^[0-9]+$@', $data ) != 0 );
    }
}
