<?php

/**
 * HTTP Response Header Field Validator Class: Server | Validation\Headers\Response\Server.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Response;

use Next\Validation\HTTP\Headers\Header;    # HTTP Headers Validator Interface
use Next\Components\Object;                 # Object Class

/**
 * The 'Server' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.38
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class Server extends Object implements Header {

    /**
     * Validates Server Header Field in according to RFC 2616 Section 14.38
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Server = "Server" ":" 1*( product | comment )
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.38
     *  RFC 2616 Section 14.38
     */
    public function validate() : bool {

        /**
         * @internal
         * Impossible to validate due uncertain number of different
         * servers around the world
         */
        return TRUE;
    }
}
