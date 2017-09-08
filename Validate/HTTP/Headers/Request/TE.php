<?php

/**
 * HTTP Request Header Field Validator Class: TE | Validate\Headers\Request\TE.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;            # HTTP Protocol Headers Interface
use Next\Components\Object;                        # Object Class
use Next\Validate\IANA\ContentEncoding as IANA;    # IANA Content-Encoding Validation Class

/**
 * TE Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class TE extends Object implements Headers {

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
    public function validate() {

        preg_match(

            sprintf( '@^(?<coding>trailers|%s(?:;q=(?<quality>%s))?)$@x',

            IANA::ENCODING, self::FLOAT ), $this -> options -> value, $match
        );

        return ( count( $match ) != 0 );
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
