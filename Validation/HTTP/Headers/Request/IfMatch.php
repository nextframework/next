<?php

/**
 * HTTP Request Header Field Validator Class: If-Match | Validation\Headers\Request\IfMatch.php
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
 * The 'If-Match' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.24
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class IfMatch extends Object implements Header {

    /**
     * Validates If-Match Header Field in according to RFC 2616 Section 14.24
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        If-Match = "If-Match" ":" ( "*" | 1#entity-tag )
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.24
     *  RFC 2616 Section 14.24
     */
    public function validate() : bool {

        // We can't validate ETags yet, so...

        return TRUE;
    }
}
