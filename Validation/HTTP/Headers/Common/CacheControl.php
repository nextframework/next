<?php

/**
 * HTTP Common Header Field Validator Class: Cache-Control | Validation\Headers\Common\CacheControl.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Common;

use Next\Validation\HTTP\Headers\Header;    # HTTP Headers Validator Interface
use Next\Components\Object;                 # Object Class

/**
 * The 'Cache-Control' Header Validator checks if input string is valid in
 * accordance to RFC 2616 Section 14.9
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\HTTP\Headers\Header
 *             Next\Components\Object
 */
class CacheControl extends Object implements Header {

    /**
     * Cache Request Directive
     *
     * @var string
     */
    const DIR  = '(no-cache|
                   no-store|
                   max-age=[0-9]+|
                   max-stale(=[0-9]+)?|
                   min-fresh[0-9]+|
                   no-transform|
                   only-if-cached|
                   public|
                   private|
                   must-revalidate|
                   proxy-revalidate|
                   s-maxage[0-9]+
                  )';

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Cache-Control Header Field in according to RFC 2616 Section 14.9
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     * Cache-Control   = "Cache-Control" ":" 1#cache-directive
     *
     *        cache-directive = cache-request-directive
     *             | cache-response-directive
     *
     *        cache-request-directive =
     *               "no-cache"
     *             | "no-store"
     *             | "max-age" "=" delta-seconds
     *             | "max-stale" [ "=" delta-seconds ]
     *             | "min-fresh" "=" delta-seconds
     *             | "no-transform"
     *             | "only-if-cached"
     *             | cache-extension
     *
     *        cache-response-directive =
     *               "public"
     *             | "private" [ "=" <"> 1#field-name <"> ]
     *             | "no-cache" [ "=" <"> 1#field-name <"> ]
     *             | "no-store"
     *             | "no-transform"
     *             | "must-revalidate"
     *             | "proxy-revalidate"
     *             | "max-age" "=" delta-seconds
     *             | "s-maxage" "=" delta-seconds
     *             | cache-extension
     *
     *        cache-extension = token [ "=" ( token | quoted-string ) ]
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.9
     *  RFC 2616 Section 14.9
     */
    public function validate() : bool {

        $test = preg_match(

                     sprintf(

                         '@^(?<directive>%s|(?<tokens>%s=[\'"]?%s[\'"]?)*?)$@x',

                         self::DIR, self::TOKEN, self::TOKEN
                     ),

                     $this -> options -> value
                 );

        return ( $test != 0 );
    }
}