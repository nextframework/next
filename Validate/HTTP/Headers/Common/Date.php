<?php

namespace Next\Validate\HTTP\Headers\Common;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * RFC 2616 Date Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Date extends Object implements Headers {

    /**
     * Validates Date Header Field in according to RFC 2616 Section 14.18
     *
     * <p>
     *     One thing different is to us, this is NOT a Request Header Field
     *     but a Common Header Field, because bot Request and Response
     *     can use it
     * </p>
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Date  = "Date" ":" HTTP-date
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
     * @param string $data
     *   Data to validate
     *
     * @return boolean
     *   TRUE if valid and FALSE otherwise
     *
     * @link
     *   http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.18
     *   RFC 2616 Section 14.18
     */
    public function validate( $data ) {
        return ( gmdate( 'D, d M Y H:i:s T', strtotime( $data ) ) == $data );
    }
}
