<?php

/**
 * HTTP Request Header Field Validator Class: Cookie | Validation\Headers\Request\Cookie.php
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
 * The 'Cookie' Header Validator checks if input string is valid for
 * a Cookie string
 *
 * Even though this is not an official as per the RFC, it's widely
 * accepted and used as one
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class Cookie extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Cookie Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Cookie: cookiename[ "=" cookievalue ];
     *
     *        cookiename  = token
     *
     *        cookievalue = token
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link http://en.wikipedia.org/wiki/HTTP_Cookie
     */
    public function validate() : bool {

        $match = preg_match(

                     sprintf( '/^(?<name>%s)(?:=(?<value>[\'"]?%s[\'"]?))?$/', self::TOKEN, self::TOKEN ),

                     $this -> options -> value
                 );

        return ( $match != 0 );
    }
}
