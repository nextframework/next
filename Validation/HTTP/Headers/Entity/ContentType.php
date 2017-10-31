<?php

/**
 * HTTP Entity Header Field Validator Class: Content-Type | Validation\Headers\Entity\ContentType.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Entity;

use Next\Validation\HTTP\Headers\Header;    # HTTP Headers Validator Interface
use Next\Components\Object;                 # Object Class
use Next\Validation\IANA\MIME as IANA;      # IANA MIME-Type Validation Class

/**
 * The 'Content-Type' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.17
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 *             Next\Validation\IANA\MIME
 */
class ContentType extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Content-Type Header Field in according to RFC 2616 Section 14.17
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Content-Type   = "Content-Type" ":" media-type
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17
     *  RFC 2616 Section 14.17
     */
    public function validate() : bool {

        /**
         * @internal
         * The validations Rules are the same of Accept Validator
         * except this one does not considerate multiple values
         * nor Quality Values, but may accept some tokens
         */
        preg_match(

            sprintf(

                '@^(?<range>%s)(?:;\s*(?<tokens>%s=[\'"]?%s[\'"]?;?)*?)?$@x',

                IANA::RANGE, self::TOKEN, self::TOKEN
            ),

            $this -> options -> value, $match
        );

        /**
         * @internal
         * array_filter() is being used in order to ignore
         * some groups / subgroups without values
         */
        $match = array_filter( $match );

        if( count( $match ) != 0 ) {

            /**
             * @internal
             * General Format is correct
             * Let's check chosen charset against IANA's MIME Types Registry
             *
             * Only Full MIME's will be analyzed. E.g.: text/html
             * Partial MIME's, like "text/*" (without quotes), don't, being automatically accepted
             */
            if( preg_match( sprintf( '@^%s$@', IANA::MIME ), $match['range'], $mime ) != 0 ) {

                $IANA = new IANA( [ 'value' => $mime[ 0 ] ] );

                if( $IANA -> validate() !== FALSE ) return TRUE;

                // Invalid as Full MIME

                return FALSE;
            }

            // Valid as Partial MIME

            return TRUE;
        }

        // Invalid everything xD

        return FALSE;
    }
}
