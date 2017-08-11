<?php

/**
 * HTTP Response Header Field Validator Class: Accept-Ranges | Validate\Headers\Response\AcceptRanges.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Response;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * RFC 2616 Accept-Ranges Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class AcceptRanges extends Object implements Headers {

    /**
     * Validates Accept-Ranges Header Field in according to RFC 2616 Section 14.5
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Accept-Ranges     = "Accept-Ranges" ":" acceptable-ranges
     *
     *        acceptable-ranges = 1#range-unit | "none"
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.5
     *  RFC 2616 Section 14.5
     */
    public function validate() {

        return ( strcasecmp( $this -> options -> value, 'none'  ) == 0 ||
                 strcasecmp( $this -> options -> value, 'bytes' ) == 0 );
    }
}
