<?php

/**
 * HTTP Request Header Field Validator Class: Host | Validate\Headers\Request\Host.php
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
 * Host Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Host extends Object implements Headers {

    /**
     * Host Validation RegExp
     *
     * @var string
     */
    const HOST = '/^(?:(?:(?:http|ftp)s?):\/\/)?[\w#:.?+=&%@!\/-]+(:[0-9]+)?/';

    /**
     * Validates Host Header Field in according to RFC 2616 Section 14.23
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Host = "Host" ":" host [ ":" port ] ;
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.23
     *  RFC 2616 Section 14.23
     */
    public function validate() {
        return ( preg_match( self::HOST, $this -> options -> value ) != 0 );
    }

    // Parameterizable Interface Method Overriding

    /**
     * Set Class Options.
     * Defines which Parameter Options are known by the Validator Class
     *
     * @return array
     *  An array with Custom Options overriding or complementing Object defaults
     */
    public function setOptions() {
        return [ 'value' => [ 'required' => TRUE ] ];
    }
}
