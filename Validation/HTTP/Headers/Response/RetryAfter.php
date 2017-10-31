<?php

/**
 * HTTP Response Header Field Validator Class: Retry-After | Validation\Headers\Response\RetryAfter.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Response;

use Next\Validation\HTTP\Headers\Header;         # HTTP Headers Validator Interface
use Next\Components\Object;                      # Object Class
use Next\Validation\HTTP\Headers\Common\Date;    # HTTP Headers Date Validator Class

/**
 * The 'Retry-After' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.37
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 *             Next\Validation\HTTP\Headers\Common\Date
 */
class RetryAfter extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Retry-After Header Field in according to RFC 2616 Section 14.37
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
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
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.37
     *  RFC 2616 Section 14.37
     */
    public function validate() : bool {

        $data = $this -> options -> value;

        // Trying to validate as HTTP-date

        $validator = new Date( [ 'value' => $data ] );

        if( ! $validator -> validate() ) {

            // Validating as Delta Seconds

            return ( preg_match( '/^[1-9][0-9]*$/', $data ) != 0 );
        }

        return TRUE;
    }
}
