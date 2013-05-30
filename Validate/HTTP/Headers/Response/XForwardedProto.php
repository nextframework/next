<?php

namespace Next\Validate\HTTP\Headers\Response;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * X-Forwarded-Proto Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XForwardedProto extends Object implements Headers {

    /**
     * Validates X-Forwarded-Proto Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        X-Forwarded-Proto = "X-Forwarded-Proto" ":" https
     * </code>
     *
     * @param string $data
     *   Data to validate
     *
     * @return boolean
     *   TRUE if valid and FALSE otherwise
     *
     * @link
     *   http://www.geekisp.com/faq/6_65_en.html
     *
     * @link
     *   http://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Common_non-standard_response_headers
     */
    public function validate( $data ) {
        return ( strcasecmp( $data, 'https' ) == 0 );
    }
}
