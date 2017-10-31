<?php

/**
 * HTTP Response Header Field Validator Class: Age | Validation\Headers\Response\Age.php
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
 * The 'Age' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.6
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class Age extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates AAge Header Field in according to RFC 2616 Section 14.6
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Age = "Age" ":" age-value
     *
     *        age-value = delta-seconds
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.6
     *  RFC 2616 Section 14.6
     */
    public function validate() : bool {

        // Age Header value must be a positive integer representing the seconds

        return ( preg_match( '@^[0-9]+$@', $this -> options -> value ) != 0 );
    }
}
