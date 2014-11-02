<?php

namespace Next\Validate\HTTP\Headers\Common;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Connection Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Connection extends Object implements Headers {

    /**
     * Validates Connection Header Field in according to RFC 2616 Section 14.10
     *
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Connection = "Connection" ":" 1#(connection-token)
     *
     *        connection-token = token
     * </code>
     *
     * @param string $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.10
     *  RFC 2616 Section 14.10
     */
    public function validate( $data ) {
        return ( strcasecmp( $data, 'close' ) == 0 || strcasecmp( $data, 'keep-alive' ) == 0 );
    }
}
