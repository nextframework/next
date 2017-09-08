<?php

/**
 * HTTP Common Header Field Validator Class: Transfer-Encoding | Validate\Headers\Common\TransferEncoding.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Common;

use Next\Validate\HTTP\Headers\Headers;            # HTTP Protocol Headers Interface
use Next\Components\Object;                        # Object Class
use Next\Validate\IANA\ContentEncoding as IANA;    # IANA Content-Encoding Validation Class

/**
 * Tranfer-Encoding Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class TransferEncoding extends Object implements Headers {

    /**
     * Validates Transfer-Encoding Header Field in according to RFC 2616 Section 14.41
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Transfer-Encoding       = "Transfer-Encoding" ":" 1#transfer-coding
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.41
     *  RFC 2616 Section 14.41
     */
    public function validate() {

        $test = preg_match(

            sprintf( '@^(chunked|%s)$@i', IANA::ENCODING ),

            $this -> options -> value
        );

        return ( $test != 0 );
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
