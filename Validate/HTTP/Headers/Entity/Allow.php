<?php

/**
 * HTTP Entity Header Field Validator Class: Allow | Validate\Headers\Entity\Allow.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Entity;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Allow Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Allow extends Object implements Headers {

    /**
     * Valid Methods
     *
     * @var string
     */
    const METHODS = '(?<method>OPTIONS|GET|HEAD|POST|PUT|DELETE|TRACE|CONNECT)';

    /**
     * Validates Allow Header Field in according to RFC 2616 Section 14.7
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Allow   = "Allow" ":" #Method
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.7
     *  RFC 2616 Section 14.7
     */
    public function validate() {

        $test = preg_match(

            sprintf( '@^%s$@i', self::METHODS ),

            $this -> options -> value
        );

        return ( $test != 0 );
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
