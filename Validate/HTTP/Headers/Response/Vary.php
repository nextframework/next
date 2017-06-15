<?php

/**
 * HTTP Response Header Field Validator Class: Vary | Validate\Headers\Response\Vary.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Validate\HTTP\Headers\Response;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * RFC 2616 Vary Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Vary extends Object implements Headers {

    /**
     * Validates Vary Header Field in according to RFC 2616 Section 14.44
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Vary  = "Vary" ":" ( "*" | 1#field-name )
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.44
     *  RFC 2616 Section 14.44
     */
    public function validate() {

        /**
         * @internal
         * Not necessary because field-name can be a RFC 2616 Header
         * or a user-defined field as well
         */
        return TRUE;
    }
}
