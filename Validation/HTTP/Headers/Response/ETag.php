<?php

/**
 * HTTP Response Header Field Validator Class: E-Tag | Validate\Headers\Response\ETag.php
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
 * RFC 2616 ETag Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ETag extends Object implements Header {

    /**
     * Validates ETag Header Field in according to RFC 2616 Section 14.19
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        ETag = "ETag" ":" entity-tag
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.19
     *  RFC 2616 Section 14.19
     */
    public function validate() {
        return TRUE;
    }
}