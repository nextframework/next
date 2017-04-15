<?php

namespace Next\Validate\HTTP\Headers\Response;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * X-Frame-Options Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XFrameOptions extends Object implements Headers {

    /**
     * Validates X-Frame-Options Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p>This acts as Clickjacking Protection</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        X-Frame-Options = "X-Frame-Options" ":" ( deny | sameorigin )
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://blogs.msdn.com/b/ie/archive/2009/01/27/ie8-security-part-vii-clickjacking-defenses.aspx
     *
     * @link
     *  http://en.wikipedia.org/wiki/Clickjacking
     *
     * @link
     *  http://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Common_non-standard_response_headers
     */
    public function validate() {

        return ( strcasecmp( $this -> options -> value, 'deny'       ) == 0 ||
                 strcasecmp( $this -> options -> value, 'sameorigin' ) == 0 );
    }
}
