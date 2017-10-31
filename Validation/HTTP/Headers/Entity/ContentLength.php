<?php

/**
 * HTTP Entity Header Field Validator Class: Content-Length | Validation\Headers\Entity\ContentLength.php
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
 * The 'Content-Length' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.13
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class ContentLength extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Content-Length Header Field in according to RFC 2616 Section 14.13
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Content-Length    = "Content-Length" ":" 1*DIGIT
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.13
     *  RFC 2616 Section 14.13
     */
    public function validate() : bool {
        return ( preg_match( '/^[0-9]+$/', $this -> options -> value ) != 0 );
    }
}
