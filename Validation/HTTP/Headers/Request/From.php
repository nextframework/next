<?php

/**
 * HTTP Request Header Field Validator Class: From | Validation\Headers\Request\From.php
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
 * The 'From' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.22
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class From extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates From Header Field in according to RFC 2616 Section 14.22
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        From   = "From" ":" mailbox
     * ````
     *
     * <p>Where mailbox is an e-mail address</p>
     *
     * <p>
     *     We are NOT validating the e-mail itself,
     *     but only the Header format, with generic e-mail pattern
     * </p>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.22
     *  RFC 2616 Section 14.22
     */
    public function validate() : bool {

        $test = preg_match(

            '/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/',

            $this -> options -> value
        );

        return ( $test != 0 );
    }
}
