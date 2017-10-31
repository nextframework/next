<?php

/**
 * HTTP Common Header Field Validator Class: Trailer | Validation\Headers\Common\Trailer.php
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
 * The 'Trailer' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.40
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class Trailer extends Object implements Header {

    /**
     * Validates Trailer Header Field in according to RFC 2616 Section 14.40
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Trailer  = "Trailer" ":" 1#field-name
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.40
     *  RFC 2616 Section 14.40
     */
    public function validate() : bool {

        // Insufficient information :(

        return TRUE;
    }
}
