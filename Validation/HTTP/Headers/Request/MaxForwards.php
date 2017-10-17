<?php

/**
 * HTTP Request Header Field Validator Class: Max-Forwards | Validate\Headers\Request\MaxForwards.php
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
 * Max-Forwards Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class MaxForwards extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Max-Forwards Header Field in according to RFC 2616 Section 14.31
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Max-Forwards   = "Max-Forwards" ":" 1*DIGIT
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.31
     *  RFC 2616 Section 14.31
     */
    public function validate() {
        return ( preg_match( '/^(?:[1-9][0-9]*)$/', $this -> options -> value ) != 0 );
    }
}