<?php

/**
 * HTTP Entity Header Field Validator Class: Content-Encoding | Validate\Headers\Entity\ContentEncoding.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Entity;

use Next\Validate\HTTP\Headers\Headers;            # HTTP Protocol Headers Interface
use Next\Components\Object;                        # Object Class
use Next\Validate\IANA\ContentEncoding as IANA;    # IANA Charset Validation Class

/**
 * Content-Encoding Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentEncoding extends Object implements Headers {

    /**
     * Validates Content-Encoding Header Field in according to RFC 2616 Section 14.11
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Content-Encoding  = "Content-Encoding" ":" 1#content-coding
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.11
     *  RFC 2616 Section 14.11
     */
    public function validate() {

        $test = preg_match(

            sprintf( '@^%s$@x', IANA::ENCODING ),

            $this -> options -> value
        );

         return ( $test != 0 );
    }
}
