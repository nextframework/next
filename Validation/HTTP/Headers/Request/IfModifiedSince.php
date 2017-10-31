<?php

/**
 * HTTP request Header Field Class: If-Modified-Since | HTTP\Headers\request\IfModifiedSince.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Request;

use Next\Validation\HTTP\Headers\Header;         # HTTP Headers Validator Interface
use Next\Components\Object;                      # Object Class
use Next\Validation\HTTP\Headers\Common\Date;    # RFC 2616 Date Header Validation Class

/**
 * The 'If-Modified-Since' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.25
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 *             Next\Validation\HTTP\Headers\Common\Date
 */
class IfModifiedSince extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates If-Modified-Since Header Field in according to RFC 2616 Section 14.25
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        If-Modified-Since = "If-Modified-Since" ":" HTTP-date
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
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.25
     *  RFC 2616 Section 14.25
     *
     * @see \Next\Validation\HTTP\Headers\Common\Date::validate()
     */
    public function validate() : bool {

        $date = new Date( [ 'value' => $this -> options -> value ] );

        return $date -> validate();
    }
}
