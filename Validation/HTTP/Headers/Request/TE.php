<?php

/**
 * HTTP Request Header Field Validator Class: TE | Validation\Headers\Request\TE.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Request;

use Next\Validation\HTTP\Headers\Header;             # HTTP Headers Validator Interface
use Next\Components\Object;                          # Object Class
use Next\Validation\IANA\ContentEncoding as IANA;    # IANA Content-Encoding Validation Class

/**
 * The 'TE' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.39
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 *             Next\Validation\IANA\ContentEncoding
 */
class TE extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates TE Header Field in according to RFC 2616 Section 14.39
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        TE        = "TE" ":" #( t-codings )
     *        t-codings = "trailers" | ( transfer-extension [ accept-params ] )
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.39
     *  RFC 2616 Section 14.39
     */
    public function validate() : bool {

        preg_match(

            sprintf( '@^(?<coding>trailers|%s(?:;q=(?<quality>%s))?)$@x',

            IANA::ENCODING, self::FLOAT ), $this -> options -> value, $match
        );

        return ( count( $match ) != 0 );
    }
}
