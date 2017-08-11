<?php

/**
 * HTTP Request Header Field Validator Class: X-Requested-With | Validate\Headers\Request\XRequestedWith.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * X-Requested-With Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XRequestedWith extends Object implements Headers {

    /**
     * Validates X-Requested-With Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        X-Requested-With = "X-Requested-With" ":" 1*( token )
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://blog.sidstamm.com/2011/01/try-out-do-not-track-http-header.html
     *
     * @link
     *  http://donottrack.us/
     *
     * @link
     *  http://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Common_non-standard_request_headers
     */
    public function validate() {

        /**
         * @internal
         * We should validate it if this header was used ONLY by
         * JavaScript Frameworks AND if the ONLY acceptable value was XMLHttpRequest.
         *
         * Since we can't restrict this, we allow everything
         */
        return TRUE;
    }
}
