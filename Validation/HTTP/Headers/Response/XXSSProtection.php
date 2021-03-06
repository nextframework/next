<?php

/**
 * HTTP Response Header Field Validator Class: X-XSS-Protection | Validation\Headers\Response\XXSSProtection.php
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
 * The 'X-XSS-Protection' Header Validator checks if input string is valid for
 * a X-XSS-Protection string
 *
 * Even though this is not an official as per the RFC, it's widely
 * accepted and used as one
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class XXSSProtection extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates X-XSS-Protection Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p>This acts as Cross-site Scripting Protection</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        X-XSS-Protection = "X-XSS-Protection" ":"
     *                           #1 [ params ]
     *
     *        params           = ";" token "=" token
     * ````
     *
     * @note So far only "mode=block" (without quotes) is accepted as valid param
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://blogs.msdn.com/b/ie/archive/2008/07/02/ie8-security-part-iv-the-xss-filter.aspx
     *
     * @link
     *  http://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Common_non-standard_response_headers
     *
     * @link
     *  http://en.wikipedia.org/wiki/Cross-site_scripting
     */
    public function validate() : bool {

        $test = preg_match(

            '/^1(?:;(?<mode>mode=[\'"]?block[\'"]?))?$/i',

            $this -> options -> value
        );

        return ( $test != 0 );
    }
}
