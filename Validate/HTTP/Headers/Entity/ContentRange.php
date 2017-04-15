<?php

namespace Next\Validate\HTTP\Headers\Entity;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Content-Range Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentRange extends Object implements Headers {

    /**
     * Validates Content-Range Header Field in according to RFC 2616 Section 14.16
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
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
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.16
     *  RFC 2616 Section 14.16
     */
    public function validate() {

        $test = preg_match(

            '/^bytes [0-9]+-[1-9][0-9]*\/([1-9][0-9]*|\*)$/i',

            $this -> options -> value
        );

        return ( $test != 0 );
    }
}
