<?php

namespace Next\Validate\HTTP\Headers\Entity;

use Next\Validate\HTTP\Headers\Headers;        # HTTP Protocol Headers Interface
use Next\Components\Object;                    # Object Class
use Next\Validate\HTTP\Headers\Common\Date;    # RFC 2616 Date Header Validation Class

/**
 * Last-Modified Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class LastModified extends Object implements Headers {

    /**
     * Validates Last-Modified Header Field in according to RFC 2616 Section 14.29
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Last-Modified  = "Last-Modified" ":" HTTP-date
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
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.29
     *  RFC 2616 Section 14.29
     *
     * @see Next\Validate\HTTP\Headers\Common\Date::validate()
     */
    public function validate() {

        $date = new Date(
            array( 'value' => $this -> options -> value )
        );

        return $date -> validate();
    }
}
