<?php

namespace Next\Validate\HTTP\Headers\Response;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * X-XSS-Protection Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XXSSProtection extends Object implements Headers {

    /**
     * Validates X-XSS-Protection Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p>This acts as Cross-site Scripting Protection</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        X-XSS-Protection = "X-XSS-Protection" ":"
     *                           #1 [ params ]
     *
     *        params           = ";" token "=" token
     * </code>
     *
     * @note So far only "mode=block" (without quotes) is accepted as valid param
     *
     * @param string $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://blogs.msdn.com/b/ie/archive/2008/07/02/ie8-security-part-iv-the-xss-filter.aspx
     *
     * @link
     *  http://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Common_non-standard_response_headers
     *
     * @link
     *  http://en.wikipedia.org/wiki/Cross-site_scripting
     */
    public function validate( $data ) {
        return ( preg_match( '/^1(?:;(?<mode>mode=[\'"]?block[\'"]?))?$/i', $data ) != 0 );
    }
}
