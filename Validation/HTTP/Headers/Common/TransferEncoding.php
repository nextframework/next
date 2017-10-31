<?php

/**
 * HTTP Common Header Field Validator Class: Transfer-Encoding | Validation\Headers\Common\TransferEncoding.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Common;

use Next\Validation\HTTP\Headers\Header;             # HTTP Headers Validator Interface
use Next\Components\Object;                          # Object Class
use Next\Validation\IANA\ContentEncoding as IANA;    # IANA Content-Encoding Validation Class

/**
 * The 'Transfer-Encoding' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.41
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class TransferEncoding extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

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
    public function validate() : bool {

        $test = preg_match(

            sprintf( '@^(chunked|%s)$@i', IANA::ENCODING ),

            $this -> options -> value
        );

        return ( $test != 0 );
    }
}
