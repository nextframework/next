<?php

/**
 * HTTP Response Header Field Validator Class: Proxy-Authenticate | Validate\Headers\Response\ProxyAuthenticate.php
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
 * RFC 2616 Proxy-Authenticate Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ProxyAuthenticate extends Object implements Header {

    /**
     * Validates Proxy-Authenticate Header Field in according to RFC 2616 Section 14.33
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Proxy-Authenticate  = "Proxy-Authenticate" ":" 1#challenge
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.33
     *  RFC 2616 Section 14.33
     */
    public function validate() {
        return TRUE;
    }
}