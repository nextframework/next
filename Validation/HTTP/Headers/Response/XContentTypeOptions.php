<?php

/**
 * HTTP Response Header Field Validator Class: X-Content-Type-Options | Validation\Headers\Response\XContentTypeOptions.php
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
 * The 'X-Content-Type-Options' Header Validator checks if input string is
 * valid for a X-Content-Type-Options string
 *
 * Even though this is not an official as per the RFC, it's widely
 * accepted and used as one
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class XContentTypeOptions extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates X-Content-Type-Options Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p>This avoids Internet Explorer from MIME-sniffing</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        X-Content-Type-Options = "X-Content-Type-Options" ":" nosniff
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://blogs.msdn.com/b/ie/archive/2008/09/02/ie8-security-part-vi-beta-2-update.aspx
     *
     * @link
     *  http://asert.arbornetworks.com/2009/03/mime-sniffing-and-phishing/
     *
     * @link
     *  http://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Common_non-standard_response_headers
     */
    public function validate() : bool {
        return ( strcasecmp( $this -> options -> value, 'nosniff' ) == 0 );
    }
}
