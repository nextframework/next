<?php

/**
 * HTTP Response Header Field Validator Class: Set-Cookie | Validate\Headers\Response\SetCookie.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\HTTP\Headers\Response;

use Next\Validate\HTTP\Headers\Headers;     # HTTP Protocol Headers Interface
use Next\Components\Object;                 # Object Class

/**
 * Date Header Validator
 */
use Next\Validate\HTTP\Headers\Common\Date;

/**
 * Set-Cookie Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class SetCookie extends Object implements Headers {

    /**
     * Validates Set-Cookie Header Field in according to RFC 2109 Section 4.2.2
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Set-Cookie: "Set-Cookie:" cookies
     *
     *        cookies         =       1#cookie
     *        cookie          =       NAME "=" VALUE *(";" cookie-av)
     *        NAME            =       attr
     *        VALUE           =       value
     *        cookie-av       =       "Comment" "=" value
     *                        |       "Domain" "=" value
     *                        |       "Max-Age" "=" value
     *                        |       "Path" "=" value
     *                        |       "Secure"
     *                        |       "Version" "=" 1*DIGIT
     * </code>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://tools.ietf.org/html/rfc2109#section-4.2.2
     *  RFC 2109 Section 4.2.2
     *
     * @link
     *  http://en.wikipedia.org/wiki/HTTP_Cookie
     */
    public function validate() {

        preg_match(

            sprintf(

                '/(?:
                    (?<name>%s)\="(?<value>%s)"

                    (?:;\s*
                        version\="(?<version>%s)"
                    )?

                    (?:;\s*
                        comment\="(?<comment>[^ \t\n\r\f\v]+)"
                    )?

                    (?:;\s*
                        (?:expires|max-age)\="(?<expires>[ ,:a-zA-Z0-9-]+)"
                    )?

                    (?:;\s*
                        path\="(?<path>\/[\w\#:.?+=&%%@!\/-]*)"
                    )?

                    (?:;\s*
                        domain\="(?<domain>[\w\#:.?+=&%%@!\/-]+)"
                    )?

                    (?:;\s*
                        (?<secure>secure)
                    )?

                    (?:;\s*
                        (?<httponly>httponly)
                    )?
                  )
                /ix',

                self::TOKEN, self::TOKEN, self::FLOAT
            ),

            $this -> options -> value, $matches
         );

        $matches = array_filter( $matches );

        // Validating Expiration Date, if present

        if( isset( $matches['expires'] ) ) {

            $date = new Date(
                array( 'value' => $matches['expires'] )
            );

            if( ! $date -> validate() ) return FALSE;
        }

        return ( $matches != 0 );
    }
}
