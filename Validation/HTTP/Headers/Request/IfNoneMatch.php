<?php

/**
 * HTTP Request Header Field Validator Class: If-None-Match | Validation\Headers\Request\IfNoneMatch.php
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
 * The 'If-None-Match' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.26
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class IfNoneMatch extends Object implements Header {

    /**
     * Validates If-None-Match Header Field in according to RFC 2616 Section 14.26
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        If-None-Match = "If-None-Match" ":" ( "*" | 1#entity-tag )
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.26
     *  RFC 2616 Section 14.26
     */
    public function validate() : bool {

        // We can't validate ETags yet, so...

        return TRUE;
    }
}
