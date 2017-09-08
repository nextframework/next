<?php

/**
 * HTTP Status Code Validator Class | Validate\Headers\Code.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Validates HTTP Status Codes
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Code extends Object implements Headers {

    /**
     * Official HTTP Status Codes List
     *
     * @var string
     *
     * @see https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#1xx_Informational_responses
     * @see https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     */
    const OFFICIAL_CODES = '(
        10[012]|                              # [ Informational 1xx ]
        2(0[0-8]|26)|                         # [ Successful 2xx ]
        30[0-8]|                              # [ Redirection 3xx ]
        4(0[0-9]|1[0-8]|2[1234689]|31|51)|    # [ Client Error 4xx ]
        5(0[0-8]|1[01])                       # [ Server Error 5xx ]
    )';

    /**
     * Unofficial HTTP Status Codes List
     *
     * @var string
     *
     * @see https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#Unofficial_codes
     */
    const UNOFFICIAL_CODES = '(
        103|                                  # [ Informational 1xx ]
        4([25]0|4[049]|51|9[56789])|          # [ Client Error 4xx ]
        5(2[0-7]|09|30|98)                    # [ Server Error 5xx ]
    )';

    /**
     * Validates HTTP Status Code
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        if( preg_match( sprintf( '/%s/x', self::OFFICIAL_CODES ), $this -> options -> value ) == 0 &&
                preg_match( sprintf( '/%s/x', self::UNOFFICIAL_CODES ), $this -> options -> value ) == 0 ) {

            return FALSE;
        }

        return TRUE;
    }

    // Parameterizable Interface Method Overriding

    /**
     * Set Class Options.
     * Defines which Parameter Options are known by the Validator Class
     *
     * @return array
     *  An array with Custom Options overriding or complementing Object defaults
     */
    public function setOptions() {
        return [ 'value' => [ 'required' => TRUE ] ];
    }
}
