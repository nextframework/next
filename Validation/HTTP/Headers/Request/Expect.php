<?php

/**
 * HTTP Request Header Field Validator Class: Expect | Validate\Headers\Request\Expect.php
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
 * Expect Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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
    public function validate() {

        // Insufficient informations

        return TRUE;
    }
}
