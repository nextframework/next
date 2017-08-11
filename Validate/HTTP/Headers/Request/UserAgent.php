<?php

/**
 * HTTP Request Header Field Validator Class: User-Agent | Validate\Headers\Request\UserAgent.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * User-Agent Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class UserAgent extends Object implements Headers {

    /**
     * Validates User-Agent Header Field in according to RFC 2616 Section 14.43
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        User-Agent = "User-Agent" ":" 1*( product | comment )
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.43
     *  RFC 2616 Section 14.43
     */
    public function validate() {

        // Impossible to validate because everything can be used as User-Agent

        return TRUE;
    }
}
