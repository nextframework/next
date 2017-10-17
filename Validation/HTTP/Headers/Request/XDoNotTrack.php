<?php

/**
 * HTTP Request Header Field Validator Class: X-Do-Not-Track | Validate\Headers\Request\XDoNotTrack.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Request;

use Next\Validation\HTTP\Headers\Header;    # HTTP Headers Validator Interface
use Next\Components\Object;                 # Object Class

/**
 * X-Do-Not-Track Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XDoNotTrack extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates X-Do-Not-Track Header Field
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *         X-Do-Not-Track = "X-Do-Not-Track" ":" 1*( 1 | 0 )
     * ````
     *
     * <p>
     *     This is not a RFC header BUT is only accepted just as one
     *     even without guarantees about its functionality
     * </p>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://hackademix.net/2010/12/28/x-do-not-track-support-in-noscript/
     *
     * @link
     *  http://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Common_non-standard_request_headers
     *
     * @link
     *  http://en.wikipedia.org/wiki/X-Do-Not-Track
     */
    public function validate() {
        return ( (int) $this -> options -> value == 1 || (int) $this -> options -> value == 0 );
    }
}
