<?php

/**
 * HTTP Common Header Field Validator Class: Connection | Validate\Headers\Common\Connection.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Common;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Connection Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Connection extends Object implements Headers {

    /**
     * Validates Connection Header Field in according to RFC 2616 Section 14.10
     *
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Connection = "Connection" ":" 1#(connection-token)
     *
     *        connection-token = token
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.10
     *  RFC 2616 Section 14.10
     */
    public function validate() {
        return in_array( $this -> options -> value, [ 'close', 'keep-alive' ] );
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
