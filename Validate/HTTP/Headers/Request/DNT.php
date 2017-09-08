<?php

/**
 * HTTP Request Header Field Validator Class: DNT | Validate\Headers\Request\DNT.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
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
    public function validate() {
        return ( (int) $this -> options -> value == 1 || (int) $this -> options -> value == 0 );
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