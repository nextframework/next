<?php

/**
 * HTTP Entity Header Field Validator Class: Allow | Validate\Headers\Entity\Allow.php
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
 * Via Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Via extends Object implements Headers {

    /**
     * Validates Via Header Field in according to RFC 2616 Section 14.45
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Via =  "Via" ":" 1#( received-protocol received-by [ comment ] )
     *
     *        received-protocol = [ protocol-name "/" ] protocol-version
     *        protocol-name     = token
     *        protocol-version  = token
     *        received-by       = ( host [ ":" port ] ) | pseudonym
     *        pseudonym         = token
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.45
     *  RFC 2616 Section 14.45
     */
    public function validate() {

        $test = preg_match(

            sprintf(

                '/(?:
                    (?:(?<protocol>%s)\/)?                    # Optional Protocol
                       (?<version>%s)                         # Required Version
                  )\s*

                  (?<receiver>
                      (?:<host>[^:]+)(?:<port>:[0-9]+)?|%s    # URL with optional Port or a pseudonym
                  )\s*

                  (?<comment>.*)?                             # Comments are everything in the end of string
                /x',

                self::TOKEN, self::TOKEN, self::TOKEN
            ),

            $this -> options -> value, $match
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
