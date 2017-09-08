<?php

/**
 * HTTP Request Header Field Validator Class: Cookie | Validate\Headers\Request\Cookie.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Cookie Header Validation Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Cookie extends Object implements Headers {

    /**
     * Validates Cookie Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Cookie: cookiename[ "=" cookievalue ];
     *
     *        cookiename  = token
     *
     *        cookievalue = token
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link http://en.wikipedia.org/wiki/HTTP_Cookie
     */
    public function validate() {

        $match = preg_match(

                     sprintf( '/^(?<name>%s)(?:=(?<value>[\'"]?%s[\'"]?))?$/', self::TOKEN, self::TOKEN ),

                     $this -> options -> value
                 );

        return ( $match != 0 );
    }

    // Parameterizable Interface Method Overriding

    /**
     * Set Class Options.
     * Defines Parameter Options requirements rules
     *
     * @return array
     *  An array with Custom Options overriding or complementing Object defaults
     */
    public function setOptions() {
        return [ 'value' => [ 'required' => TRUE ] ];
    }
}
