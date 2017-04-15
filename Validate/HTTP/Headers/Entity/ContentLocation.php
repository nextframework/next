<?php

namespace Next\Validate\HTTP\Headers\Entity;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Content-Location Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentLocation extends Object implements Headers {

    /**
     * Validates Content-Location Header Field in according to RFC 2616 Section 14.14
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Content-Location = "Content-Location" ":"
     *                           ( absoluteURI | relativeURI )
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.14
     *  RFC 2616 Section 14.14
     */
    public function validate() {

        $test = preg_match(

            sprintf(

                '/^(?:%s|%s)$/x',

                Headers::ABSOLUTE_URI, Headers::RELATIVE_URI
            ),

            $this -> options -> value, $match
        );

        return ( $match != 0 );
    }
}
