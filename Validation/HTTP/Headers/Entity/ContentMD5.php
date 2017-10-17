<?php

/**
 * HTTP Entity Header Field Validator Class: Content-MD5 | Validate\Headers\Entity\ContentMD5.php
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
 * Content-MD5 Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentMD5 extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Content-MD5 Header Field in according to RFC 2616 Section 14.15
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Content-MD5   = "Content-MD5" ":" md5-digest
     *        md5-digest   = <base64 of 128 bit MD5 digest as per RFC 1864>
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.15
     *  RFC 2616 Section 14.15
     */
    public function validate() {

        preg_match( '/^(?<hash>[a-zA-Z0-9\+\=\/]+)$/', $this -> options -> value, $match );

        return ( count( $match ) != 0 && strlen( base64_decode( $match['hash'] ) ) == 32 );
    }
}
