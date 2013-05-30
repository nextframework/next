<?php

namespace Next\Validate\HTTP\Headers\Response;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * X-Content-Type-Options Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XContentTypeOptions extends Object implements Headers {

    /**
     * Validates X-Content-Type-Options Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p>This avoids Internet Explorer from MIME-sniffing</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        X-Content-Type-Options = "X-Content-Type-Options" ":" nosniff
     * </code>
     *
     * @param string $data
     *   Data to validate
     *
     * @return boolean
     *   TRUE if valid and FALSE otherwise
     *
     * @link
     *   http://blogs.msdn.com/b/ie/archive/2008/09/02/ie8-security-part-vi-beta-2-update.aspx
     *
     * @link
     *   http://asert.arbornetworks.com/2009/03/mime-sniffing-and-phishing/
     *
     * @link
     *   http://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Common_non-standard_response_headers
     */
    public function validate( $data ) {
        return ( strcasecmp( $data, 'nosniff' ) == 0 );
    }
}
