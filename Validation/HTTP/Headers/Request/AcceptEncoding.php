<?php

/**
 * HTTP Request Header Field Validator Class: Accept-Encoding | Validate\Headers\Request\AcceptEncoding.php
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
 * Accept-Encoding Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class AcceptEncoding extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Accept-Charset Header Field in according to RFC 2616 Section 14.3
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Accept-Encoding  = "Accept-Encoding" ":"
     *                           1#( codings [ ";" "q" "=" qvalue ] )
     *
     *        codings          = ( content-coding | "*" )
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.3
     *  RFC 2616 Section 14.3
     */
    public function validate() {

        $test = preg_match(

            sprintf(

                '@^(?<encoding>\*|%s)(?:;q=(?<quality>%s))?$@x',

                IANA::ENCODING, self::FLOAT
            ),

            $this -> options -> value
        );

        return ( $test != 0 );
    }
}
