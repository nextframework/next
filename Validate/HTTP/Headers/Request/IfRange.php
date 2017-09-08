<?php

/**
 * HTTP Request Header Field Validator Class: If-Range | Validate\Headers\Request\IfRange.php
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
 * If-Range Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class IfRange extends Object implements Headers {

    /**
     * Validates If-Range Header Field in according to RFC 2616 Section 14.27
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        If-Range = "If-Range" ":" ( entity-tag | HTTP-date )
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.27
     *  RFC 2616 Section 14.27
     */
    public function validate() {

        /**
         * @internal
         *
         * We can't validate ETags yet, so even we can validate HTTP-date,
         * we can't offer a full validator...
         */
        return TRUE;
    }
}
