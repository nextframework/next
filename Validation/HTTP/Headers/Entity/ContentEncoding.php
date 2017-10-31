<?php

/**
 * HTTP Entity Header Field Validator Class: Content-Encoding | Validation\Headers\Entity\ContentEncoding.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Entity;

use Next\Validation\HTTP\Headers\Header;             # HTTP Headers Validator Interface
use Next\Components\Object;                          # Object Class
use Next\Validation\IANA\ContentEncoding as IANA;    # IANA Charset Validation Class

/**
 * The 'Content-Encoding' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.11
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 *             Next\Validation\IANA\ContentEncoding
 */
class ContentEncoding extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Content-Encoding Header Field in according to RFC 2616 Section 14.11
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Content-Encoding  = "Content-Encoding" ":" 1#content-coding
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.11
     *  RFC 2616 Section 14.11
     */
    public function validate() : bool {

        $test = preg_match(

            sprintf( '@^%s$@x', IANA::ENCODING ),

            $this -> options -> value
        );

         return ( $test != 0 );
    }
}
