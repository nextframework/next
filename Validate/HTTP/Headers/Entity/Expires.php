<?php

/**
 * HTTP Entity Header Field Class: Expires | HTTP\Headers\Fields\Entity\Expires.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Entity;

use Next\Validate\HTTP\Headers\Headers;        # HTTP Protocol Headers Interface
use Next\Components\Object;                    # Object Class
use Next\Validate\HTTP\Headers\Common\Date;    # RFC 2616 Date Header Validation Class

/**
 * Expires Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Expires extends Object implements Headers {

    /**
     * Validates Age Header Field in according to RFC 2616 Section 14.21
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Expires = "Expires" ":" HTTP-date
     *
     *        HTTP-date    = rfc1123-date
     *
     *        rfc1123-date = wkday "," SP date SP time SP "GMT"
     *
     *        date        = 2DIGIT SP month SP 4DIGIT
     *                      ; day month year (e.g., 02 Jun 1982)
     *
     *        time        = 2DIGIT ":" 2DIGIT ":" 2DIGIT
     *                      ; 00:00:00 - 23:59:59
     *
     *        wkday       = "Mon" | "Tue" | "Wed"
     *                    | "Thu" | "Fri" | "Sat" | "Sun"
     *
     *        weekday     = "Monday" | "Tuesday" | "Wednesday"
     *                    | "Thursday" | "Friday" | "Saturday" | "Sunday"
     *
     *        month       = "Jan" | "Feb" | "Mar" | "Apr"
     *                    | "May" | "Jun" | "Jul" | "Aug"
     *                    | "Sep" | "Oct" | "Nov" | "Dec"
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.21
     *  RFC 2616 Section 14.21
     *
     * @see \Next\Validate\HTTP\Headers\Common\Date::validate()
     */
    public function validate() {

        $date = new Date(
            array( 'value' => $this -> options -> value )
        );

        return $date -> validate();
    }
}
