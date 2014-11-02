<?php

namespace Next\Validate\HTTP\Headers\Request;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * DNT Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class DNT extends Object implements Headers {

    /**
     * Validates DNT (Do Not Track) Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p>
     *  It's almost the same as X-Do-Not-Track, but seems this is being
     *  acceptable more and more by modern browsers
     * </p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        DNT = "DNT" ":" 1*( 1 | 0 )
     * </code>
     *
     * @param string $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://blog.sidstamm.com/2011/01/try-out-do-not-track-http-header.html
     *
     * @link
     *  http://donottrack.us/
     *
     * @link
     *  http://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Common_non-standard_request_headers
     */
    public function validate( $data ) {

        $data = (int) $data;

        return ( $data == 1 || $data == 0 );
    }
}