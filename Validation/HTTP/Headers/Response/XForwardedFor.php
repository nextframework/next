<?php

/**
 * HTTP Response Header Field Validator Class: X-Forwarded-For | Validation\Headers\Response\XForwardedFor.php
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
 * The 'X-Forwarded-For' Header Validator checks if input string is valid for
 * a X-Forwarded-For string
 *
 * Even though this is not an official as per the RFC, it's widely
 * accepted and used as one
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
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
    public function validate() : bool {

        // Not enough Information... again :P

        return TRUE;
    }
}
