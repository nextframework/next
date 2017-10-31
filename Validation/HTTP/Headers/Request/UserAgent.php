<?php

/**
 * HTTP Request Header Field Validator Class: User-Agent | Validation\Headers\Request\UserAgent.php
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
 * The 'User-Agent' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.43
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class UserAgent extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates User-Agent Header Field in according to RFC 2616 Section 14.43
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        User-Agent = "User-Agent" ":" 1*( product | comment )
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.43
     *  RFC 2616 Section 14.43
     */
    public function validate() : bool {

        // Impossible to validate because everything can be used as User-Agent

        return TRUE;
    }
}
