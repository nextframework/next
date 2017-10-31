<?php

/**
 * HTTP Request Header Field Validator Class: Expect | Validation\Headers\Request\Expect.php
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
 * The 'Expect' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.20
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class Expect extends Object implements Header {

    /**
     * Validates Expect Header Field in according to RFC 2616 Section 14.20
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *
     *        Expect       =  "Expect" ":" 1#expectation
     *
     *        expectation  =  "100-continue" | expectation-extension
     *        expectation-extension =  token [ "=" ( token | quoted-string )
     *                                 *expect-params ]
     *
     *        expect-params =  ";" token [ "=" ( token | quoted-string ) ]
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.20
     *  RFC 2616 Section 14.20
     */
    public function validate() : bool {

        // Insufficient informations

        return TRUE;
    }
}
