<?php

/**
 * HTTP Request Header Field Validator Class: Proxy-Authorization | Validate\Headers\Request\ProxyAuthorization.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Proxy-Authorization Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ProxyAuthorization extends Object implements Headers {

    /**
     * Validates Proxy-Authorization Header Field in according to RFC 2616 Section 14.34
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Proxy-Authorization     = "Proxy-Authorization" ":" credentials
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.34
     *  RFC 2616 Section 14.34
     */
    public function validate() {
        return TRUE;
    }
}
