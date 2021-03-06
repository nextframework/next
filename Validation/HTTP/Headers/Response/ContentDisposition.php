<?php

/**
 * HTTP Response Header Field Validator Class: Content-Disposition | Validation\Headers\Response\ContentDisposition.php
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
 * Date Header Validator
 */
use Next\Validation\HTTP\Headers\Common\Date;

/**
 * The 'Content-Disposition' Header Validator checks if input string is valid in
 * accordance to RFC 2183 Section 2
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class ContentDisposition extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Content-Disposition Header Field in according to RFC 2183 Section 2
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Content-Disposition = "Content-Disposition" ":"
     *                              disposition-type
     *                              *(";" disposition-param)
     *
     *        disposition-type := "inline"
     *                          / "attachment"
     *                          / extension-token
     *                          ; values are not case-sensitive
     *
     *        disposition-param := filename-param
     *                          / creation-date-param
     *                          / modification-date-param
     *                          / read-date-param
     *                          / size-param
     *                          / parameter
     *
     *        filename-param := "filename" "=" value
     *
     *        creation-date-param := "creation-date" "=" quoted-date-time
     *
     *        modification-date-param := "modification-date" "=" quoted-date-time
     *
     *        read-date-param := "read-date" "=" quoted-date-time
     *
     *        size-param := "size" "=" 1*DIGIT
     *
     *        quoted-date-time := quoted-string
     *                         ; contents MUST be an RFC 822 `date-time'
     *                         ; numeric timezones (+HHMM or -HHMM) MUST be used
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://tools.ietf.org/html/rfc2183#section-2
     *  RFC 2183 Section 2
     */
    public function validate() : bool {

        preg_match(

            sprintf(

                '/^(?:
                       (?<disposition>inline|attachment)

                       (?:;\s*
                              filename=(?<filename>%s\b)
                       )?

                       (?:;\s*
                              creation-date=[\'"]?(?<creation>[ ,:a-zA-Z0-9]+)[\'"]?
                       )?

                       (?:;\s*
                              modification-date=[\'"]?(?<modification>[ ,:a-zA-Z0-9]+)[\'"]?
                       )?

                       (?:;\s*
                              read-date=[\'"]?(?<read>[ ,:a-zA-Z0-9]+)[\'"]?
                       )?

                       (?:;\s*
                              size=[\'"]?(?<size>[0-9]+)[\'"]?
                       )?

                   )/xi',

                self::TOKEN
            ),

            $this -> options -> value, $matches
        );

        // Validating HTTP-dates, if present

        if( isset( $matches['creation'] ) ) {

            $date = new Date( [ 'value' => $matches['creation'] ] );

            if( ! $date -> validate() ) return FALSE;
        }

        if( isset( $matches['modification'] ) ) {

            $date = new Date( [ 'value' => $matches['modification'] ] );

            if( ! $date -> validate() ) return FALSE;
        }

        if( isset( $matches['read'] ) ) {

            $date = new Date( [ 'value' => $matches['read'] ] );

            if( ! $date -> validate() ) return FALSE;
        }

        $matches = array_filter( $matches );

        return ( count( $matches ) != 0 );
    }
}
