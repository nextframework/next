<?php

/**
 * HTTP Request Header Field Validator Class: Host | Validation\Headers\Request\Host.php
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
 * The 'Host' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.23
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class Host extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Host Header Field in according to RFC 2616 Section 14.23
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Host = "Host" ":" host [ ":" port ] ;
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.23
     *  RFC 2616 Section 14.23
     */
    public function validate() : bool {
        return ( preg_match( sprintf( '/%s/', Header::ABSOLUTE_URI ), $this -> options -> value ) != 0 );
    }
}
