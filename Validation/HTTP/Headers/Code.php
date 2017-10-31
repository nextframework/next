<?php

/**
 * HTTP Status Code Validator Class | Validation\Headers\Code.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers;

use Next\Validation\HTTP\Headers\Header;    # HTTP Headers Validator Interface
use Next\Components\Object;                 # Object Class

/**
 * The HTTP Status Code Validator checks if input string is valid to be used
 * as HTTP Status Code
 *
 * HTTP Status Code aren't sequential and not all of them are official so we
 * split the regular expressions
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class Code extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

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
    public function validate() : bool {

        if( preg_match( sprintf( '/%s/x', self::OFFICIAL_CODES ), $this -> options -> value ) == 0 &&
                preg_match( sprintf( '/%s/x', self::UNOFFICIAL_CODES ), $this -> options -> value ) == 0 ) {

            return FALSE;
        }

        return TRUE;
    }
}
