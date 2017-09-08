<?php

/**
 * HTTP Entity Header Field Validator Class: Content-Location | Validate\Headers\Entity\ContentLocation.php
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
 * Content-Location Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentLocation extends Object implements Headers {

    /**
     * Validates Content-Location Header Field in according to RFC 2616 Section 14.14
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Content-Location = "Content-Location" ":"
     *                           ( absoluteURI | relativeURI )
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.14
     *  RFC 2616 Section 14.14
     */
    public function validate() {

        $test = preg_match(

            sprintf(

                '/^(?:%s|%s)$/x',

                Headers::ABSOLUTE_URI, Headers::RELATIVE_URI
            ),

            $this -> options -> value, $match
        );

        return ( $match != 0 );
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
