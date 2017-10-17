<?php

/**
 * HTTP Common Header Field Validator Class: Pragma | Validate\Headers\Common\Pragma.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Common;

use Next\Validation\HTTP\Headers\Header;    # HTTP Headers Validator Interface
use Next\Components\Object;                 # Object Class

/**
 * Pragma Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Pragma extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Pragma Header Field in according to RFC 2616 Section 14.32
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Pragma            = "Pragma" ":" 1#pragma-directive
     *
     *        pragma-directive  = "no-cache" | extension-pragma
     *
     *        extension-pragma  = token [ "=" ( token | quoted-string ) ]
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.32
     *  RFC 2616 Section 14.32
     */
    public function validate() {

        $test = preg_match(

            sprintf( '/^(no-cache|%s=[\'"]?%s[\'"]?)$/', self::TOKEN, self::TOKEN ),

            $this -> options -> value
        );

        return ( $test != 0 );
    }
}
