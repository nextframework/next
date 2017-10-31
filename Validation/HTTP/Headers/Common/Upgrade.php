<?php

/**
 * HTTP Common Header Field Validator Class: Upgrade | Validation\Headers\Common\Upgrade.php
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
 * The 'Upgrade' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.42
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class Upgrade extends Object implements Header {

    /**
     * Validates Upgrade Header Field in according to RFC 2616 Section 14.42
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Upgrade = "Upgrade" ":" 1#product
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.42
     *  RFC 2616 Section 14.42
     */
    public function validate() : bool {

        /**
         * @internal
         *
         * Impossible to validate due uncertain number of different
         * protocols around the world
         */
        return TRUE;
    }
}
