<?php

/**
 * HTTP Entity Header Field Validator Class: Content-Range | Validation\Headers\Entity\ContentRange.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Entity;

use Next\Validation\HTTP\Headers\Header;    # HTTP Headers Validator Interface
use Next\Components\Object;                 # Object Class

/**
 * The 'Content-Range' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.16
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class ContentRange extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Content-Range Header Field in according to RFC 2616 Section 14.16
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Content-Range = "Content-Range" ":" content-range-spec
     *
     *        content-range-spec      = byte-content-range-spec
     *
     *        byte-content-range-spec = bytes-unit SP
     *                                  byte-range-resp-spec "/"
     *                                  ( instance-length | "*" )
     *
     *        byte-range-resp-spec = (first-byte-pos "-" last-byte-pos)
     *                                       | "*"
     *
     *        instance-length           = 1*DIGIT
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.16
     *  RFC 2616 Section 14.16
     */
    public function validate() : bool {

        $test = preg_match(

            '/^bytes [0-9]+-[1-9][0-9]*\/([1-9][0-9]*|\*)$/i',

            $this -> options -> value
        );

        return ( $test != 0 );
    }
}
