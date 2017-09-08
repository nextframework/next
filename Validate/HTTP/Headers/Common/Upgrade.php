<?php

/**
 * HTTP Common Header Field Validator Class: Upgrade | Validate\Headers\Common\Upgrade.php
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
 * Upgrade Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Upgrade extends Object implements Headers {

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
    public function validate() {

        /**
         * @internal
         *
         * Impossible to validate due uncertain number of different
         * protocols around the world
         */
        return TRUE;
    }
}
