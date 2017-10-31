<?php

/**
 * HTTP Entity Header Field Validator Class: Allow | Validation\Headers\Entity\Allow.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Common;

use Next\Validation\HTTP\Headers\Header;    # HTTP Headers Validator Interface
use Next\Components\Object;                 # Object Class

/**
 * The 'Via' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.45
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class Via extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Via Header Field in according to RFC 2616 Section 14.45
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Via =  "Via" ":" 1#( received-protocol received-by [ comment ] )
     *
     *        received-protocol = [ protocol-name "/" ] protocol-version
     *        protocol-name     = token
     *        protocol-version  = token
     *        received-by       = ( host [ ":" port ] ) | pseudonym
     *        pseudonym         = token
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.45
     *  RFC 2616 Section 14.45
     */
    public function validate() : bool {

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

            $this -> options -> value
        );

        return ( $test != 0 );
    }
}
