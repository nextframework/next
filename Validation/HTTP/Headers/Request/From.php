<?php

/**
 * HTTP Request Header Field Validator Class: From | Validate\Headers\Request\From.php
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
 * From Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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
    public function validate() {

        $test = preg_match(

            '/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/',

            $this -> options -> value
        );

        return ( $test != 0 );
    }
}
