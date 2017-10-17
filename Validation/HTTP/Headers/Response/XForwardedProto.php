<?php

/**
 * HTTP Response Header Field Validator Class: X-Forwarded-Proto | Validate\Headers\Response\XForwardedProto.php
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
 * X-Forwarded-Proto Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XForwardedProto extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates X-Forwarded-Proto Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        X-Forwarded-Proto = "X-Forwarded-Proto" ":" https
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.geekisp.com/faq/6_65_en.html
     *
     * @link
     *  http://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Common_non-standard_response_headers
     */
    public function validate() {
        return ( strcasecmp( $this -> options -> value, 'https' ) == 0 );
    }
}
