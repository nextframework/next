<?php

/**
 * HTTP Request Header Field Validator Class: X-Requested-With | Validation\Headers\Request\XRequestedWith.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Request;

use Next\Validation\HTTP\Headers\Header;    # HTTP Headers Validator Interface
use Next\Components\Object;                 # Object Class

/**
 * The 'X-Requested-With' Header Validator checks if input string is valid for
 * a X-Requested-With string
 *
 * Even though this is not an official as per the RFC, it's widely
 * accepted and used as one
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class XRequestedWith extends Object implements Header {

    /**
     * Validates X-Requested-With Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        X-Requested-With = "X-Requested-With" ":" 1*( token )
     * ````
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
    public function validate() : bool {

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
