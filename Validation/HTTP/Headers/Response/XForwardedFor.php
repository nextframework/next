<?php

/**
 * HTTP Response Header Field Validator Class: X-Forwarded-For | Validate\Headers\Response\XForwardedFor.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Response;

use Next\Validation\HTTP\Headers\Header;    # HTTP Headers Validator Interface
use Next\Components\Object;                 # Object Class

/**
 * X-Forwarded-For Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XForwardedFor extends Object implements Header {

    /**
     * Validates X-Forwarded-For Header Field
     *
     * <p>This is not a RFC header BUT is widely accepted just as one</p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        X-Forwarded-For = "X-Forwarded-For" ":" *( client | proxy )
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://wiki.squid-cache.org/SquidFaq/ConfiguringSquid#head-3518b69c63e221cc3cd7885415e365ffaf3dd27f
     *
     * @link
     *  http://en.wikipedia.org/wiki/X-Forwarded-For
     *
     * @link
     *  http://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Common_non-standard_response_headers
     */
    public function validate() {

        // Not enough Information... again :P

        return TRUE;
    }
}
