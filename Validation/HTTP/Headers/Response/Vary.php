<?php

/**
 * HTTP Response Header Field Validator Class: Vary | Validation\Headers\Response\Vary.php
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
 * The 'Age' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.44
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class Vary extends Object implements Header {

    /**
     * Validates Vary Header Field in according to RFC 2616 Section 14.44
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Vary  = "Vary" ":" ( "*" | 1#field-name )
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.44
     *  RFC 2616 Section 14.44
     */
    public function validate() : bool {

        /**
         * @internal
         * Not necessary because field-name can be a RFC 2616 Header
         * or a user-defined field as well
         */
        return TRUE;
    }
}
