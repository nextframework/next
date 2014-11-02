<?php

namespace Next\Validate\HTTP\Headers\Common;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * Warning Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Warning extends Object implements Headers {

    /**
     * Validates Warning Header Field in according to RFC 2616 Section 14.46
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Warning    = "Warning" ":" 1#warning-value
     *
     *        warning-value = warn-code SP warn-agent SP warn-text [SP warn-date]
     *
     *        warn-code  = 3DIGIT
     *        warn-agent = ( host [ ":" port ] ) | pseudonym
     *                        ; the name or pseudonym of the server adding
     *                        ; the Warning header, for use in debugging
     *
     *        warn-text  = quoted-string
     *
     *        warn-date  = <"> HTTP-date <">
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
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.46
     *  RFC 2616 Section 14.46
     */
    public function validate( $data ) {

        preg_match(

            sprintf(

                '/(?<code>\d{3})\s*                                                    # Code

                  (?<agent>
                      (?<host>(?:(?:(?:(?:http|ftp)s?):\/\/)?[\w\#:.?+=&%%@!\/-]+))    # Sender Host and port
                      (?<port>:[0-9]+)?|%s                                             # or a pseudonym
                  )?\s*

                  (?<message>[\'"]?[^\#]+[\'"]?)?\s*                                   # Warning Message

                  (?:\#(?<date>.*))?                                                   # Warning Date

                /x',

                self::TOKEN

            ), $data, $match
        );

        if( count( $match ) != 0 ) {

            // All valid let's validate HTTP-date (last group), if present

            if( isset( $match['date'] ) ) {

                if( gmdate( 'D, d M Y H:i:s T', strtotime( $data ) ) == $match[ 4 ] ) {

                    // Valid, incuding the HTTP-date

                    return TRUE;
                }

                // HTTP-date is invalid, but since it is optional, we can ignore it

                return TRUE;
            }

            // Valid, without the HTTP-date

            return TRUE;
        }

        return FALSE;
    }
}
