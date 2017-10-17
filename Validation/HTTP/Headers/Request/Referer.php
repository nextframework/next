<?php

/**
 * HTTP Request Header Field Validator Class: Referer | Validate\Headers\Request\Referer.php
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
 * Referer Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Referer extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Referer Header Field in according to RFC 2616 Section 14.36
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Referer        = "Referer" ":" ( absoluteURI | relativeURI )
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.36
     *  RFC 2616 Section 14.36
     */
    public function validate() {

        preg_match(

            sprintf(

                '/^(?:%s|%s)$/x',

                Header::ABSOLUTE_URI, Header::RELATIVE_URI
            ),

            $this -> options -> value, $match
        );

        return ( count( $match ) != 0 );
    }
}