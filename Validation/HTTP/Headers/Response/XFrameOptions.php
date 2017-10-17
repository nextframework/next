<?php

/**
 * HTTP Response Header Field Validator Class: X-Frame-Options | Validate\Headers\Response\XFrameOptions.php
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
 * X-Frame-Options Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XFrameOptions extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates X-Frame-Options Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p>This acts as Clickjacking Protection</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        X-Frame-Options = "X-Frame-Options" ":" ( deny | sameorigin )
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://blogs.msdn.com/b/ie/archive/2009/01/27/ie8-security-part-vii-clickjacking-defenses.aspx
     *
     * @link
     *  http://en.wikipedia.org/wiki/Clickjacking
     *
     * @link
     *  http://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Common_non-standard_response_headers
     */
    public function validate() {

        return ( strcasecmp( $this -> options -> value, 'deny'       ) == 0 ||
                 strcasecmp( $this -> options -> value, 'sameorigin' ) == 0 );
    }
}
