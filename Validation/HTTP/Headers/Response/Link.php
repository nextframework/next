<?php

/**
 * HTTP Response Header Field Validator Class: Link | Validate\Headers\Response\Link.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers\Response;

use Next\Validation\HTTP\Headers\Header;                    # HTTP Headers Validator Interface
use Next\Components\Object;                                 # Object Class
use Next\Validation\HTTP\Headers\Request\AcceptLanguage;    # Accept-Language Validator Class
use Next\Validation\IANA\MIME as IANA;                      # IANA MIME Validation Class

/**
 * Link Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Link extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Link Header Field in according to RFC 5988 Section 5
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * ````
     *        Link           = "Link" ":" #link-value
     *
     *        link-value     = "<" URI-Reference ">" *( ";" link-param )
     *
     *        link-param     = ( ( "rel" "=" relation-types )
     *                       | ( "anchor" "=" <"> URI-Reference <"> )
     *                       | ( "rev" "=" relation-types )
     *                       | ( "hreflang" "=" Language-Tag )
     *                       | ( "media" "=" ( MediaDesc | ( <"> MediaDesc <"> ) ) )
     *                       | ( "title" "=" quoted-string )
     *                       | ( "title*" "=" ext-value )
     *                       | ( "type" "=" ( media-type | quoted-mt ) )
     *                       | ( link-extension ) )
     *
     *        link-extension = ( paramname [ "=" ( ptoken | quoted-string ) ] )
     *                       | ( ext-name-star "=" ext-value )
     *
     *        ext-name-star  = paramname "*" ; reserved for RFC2231-profiled
     *                                      ; extensions.  Whitespace NOT
     *                                      ; allowed in between.
     *
     *        ptoken         = 1*ptokenchar
     *
     *        ptokenchar     = "!" | "#" | "$" | "%" | "&" | "'" | "("
     *                       | ")" | "*" | "+" | "-" | "." | "/" | DIGIT
     *                       | ":" | "<" | "=" | ">" | "?" | "@" | ALPHA
     *                       | "[" | "]" | "^" | "_" | "`" | "{" | "|"
     *                       | "}" | "~"
     *
     *        media-type     = type-name "/" subtype-name
     *
     *        quoted-mt      = <"> media-type <">
     *
     *        relation-types = relation-type
     *                       | <"> relation-type *( 1*SP relation-type ) <">
     *
     *        relation-type  = reg-rel-type | ext-rel-type
     *
     *        reg-rel-type   = LOALPHA *( LOALPHA | DIGIT | "." | "-" )
     *
     *        ext-rel-type   = URI
     * ````
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://tools.ietf.org/html/rfc2183#section-2
     *  RFC 2183 Section 2
     */
    public function validate() {

        preg_match(

            sprintf(

                '@(?:
                      (?<reference>\<%1$s\>)

                      (?:;\s*rel="(?<rel>(?:[a-z0-9.-]+|%1$s)")?

                      (?:;\s*anchor="(?<anchor>%1$s)")?

                      (?:;\s*rev="(?<rev>(?:(?:[a-z0-9.-]+|%1$s)\s*)+)")?

                      (?:;\s*hreflang="(?<lang>[a-zA-Z]{2}(?:-[a-zA-Z]{2})?)")?

                      (?:;\s*media="(?<media>\w+)")?

                      (?:;\s*title="(?<title>\w+)")?

                      (?:;\s*type="(?<type>%2$s)")?

                      (?:;\s*\w+="(?<custom>[^ \t\n\r\f\v])")*?
                  )@x',

                  Header::ABSOLUTE_URI, IANA::RANGE
            ),

            $this -> options -> value, $matches
        );

        // Validating Language, if present

        if( isset( $matches['lang'] ) ) {

            $language = new AcceptLanguage(
                [ 'value' => $matches['lang'] ]
            );

            if( ! $language -> validate() ) return FALSE;
        }

        $matches = array_filter( $matches );

        return ( count( $matches ) != 0 );
    }
}
