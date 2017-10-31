<?php

/**
 * HTTP Response Header Field Validator Class: Location | Validation\Headers\Response\Location.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Response;

use Next\Validation\HTTP\Headers\Header;    # HTTP Headers Validator Interface
use Next\Components\Object;                 # Object Class

/**
 * The 'Age' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.30
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class Location extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Location Header Field in according to RFC 2616 Section 14.30
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Location       = "Location" ":" absoluteURI
     * ````
     *
     * <p><strong>Absolute URI Definition</strong></p>
     *
     * <p>
     *     HTTP or FTP Protocols, with or without SSL Character followed by
     *     <strong>://</strong> (everything optional).
     * </p>
     *
     * <p>
     *     Then followed by one or more alphanumeric characters and/or
     *     special chars
     * </p>
     *
     * <p>Special Chars are: :.?+=&%@!\/-</p>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.30
     *  RFC 2616 Section 14.30
     */
    public function validate() : bool {

        $test = preg_match(

            sprintf( '/%s/', Header::ABSOLUTE_URI ),

            $this -> options -> value
        );

        return ( $test != 0 );
    }
}
