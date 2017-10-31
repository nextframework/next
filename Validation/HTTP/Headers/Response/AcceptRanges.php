<?php

/**
 * HTTP Response Header Field Validator Class: Accept-Ranges | Validation\Headers\Response\AcceptRanges.php
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
 * The 'Accept-Ranges' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.5
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class AcceptRanges extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Accept-Ranges Header Field in according to RFC 2616 Section 14.5
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Accept-Ranges     = "Accept-Ranges" ":" acceptable-ranges
     *
     *        acceptable-ranges = 1#range-unit | "none"
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.5
     *  RFC 2616 Section 14.5
     */
    public function validate() : bool {

        return ( strcasecmp( $this -> options -> value, 'none'  ) == 0 ||
                 strcasecmp( $this -> options -> value, 'bytes' ) == 0 );
    }
}
