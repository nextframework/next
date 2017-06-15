<?php

/**
 * HTTP Response Header Field Validator Class: Retry-After | Validate\Headers\Response\RetryAfter.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Validate\HTTP\Headers\Response;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * RFC 2616 Retry-After Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class RetryAfter extends Object implements Headers {

    /**
     * Validates Retry-After Header Field in according to RFC 2616 Section 14.37
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Retry-After  = "Retry-After" ":" ( HTTP-date | delta-seconds )
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
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.37
     *  RFC 2616 Section 14.37
     */
    public function validate() {

        $data = $this -> options -> value;

        // Trying to validate as HTTP-date

        if( gmdate( 'D, d M Y H:i:s T', strtotime( $data ) ) != $data ) {

            // Trying to validate as Delta Seconds

            if( preg_match( '/^[1-9][0-9]*$/', $data ) != 0 ) {

                return TRUE;
            }

            return FALSE;
        }

        return TRUE;
    }
}
