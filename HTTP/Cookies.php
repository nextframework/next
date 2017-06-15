<?php

/**
 * HTTP Cookies Class | HTTP\Cookies.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\HTTP;

use Next\HTTP\Headers\Fields\FieldsException;   # Header Fields Exception Class
use Next\Components\Object;                     # Object Class
use Next\Components\Collections\Lists;          # Lists Class
use Next\HTTP\Headers\Fields\Request\Cookie;    # HTTP Cookie Header Class

/**
 * HTTP Cookies Management Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Cookies extends Object {

    /**
     * Registered Cookies
     *
     * @var \Next\Components\Collections\Lists $cookies
     */
    private $cookies;

    /**
     * Additional Initialization
     */
    public function init() {
        $this -> cookies = new Lists;
    }

    /**
     * Add a Coookie
     *
     * Cookie definition: <em>cookiename[=cookievalue]</em>
     *
     * @param string|array $cookie
     *  Cookie to add
     *
     * @param string|optional $value
     *  Cookie value, in case a non RFC definition is being used
     *
     * @return \Next\HTTP\Cookies
     *  Cookies Object (Fluent Interface)
     *
     * @throws \Next\HTTP\Headers\Fields\FieldsException
     *  Invalid or mal-formed Cookie Value
     */
    public function addCookie( $cookie, $value = NULL ) {

        // Well-formed Cookie. Will be added "as is"

        if( $cookie instanceof Cookie ) {

            $this -> cookies -> add( $cookie );

            return $this;
        }

        // Recursion...

        if( is_array( $cookie ) ) {

            foreach( $cookie as $n => $v ) {

                $this -> addCookie( $n, $v );
            }

        } else {

            /**
             * @internal
             * In case \Next\HTTP\Cookies::addCookie() was invoked like:
             *
             * $cookies -> addCookie( 'cookiename', 'cookievalue' )
             *
             * Instead of:
             *
             * $cookies -> addCookie( 'cookiename=cookievalue' )
             *
             * Let's build the full Cookie representation before add it
             */
            if( ! is_null( $value ) && strpos( $value, '=' ) === FALSE ) {

                $cookie = sprintf( '%s=%s', $cookie, $value );
            }

            try {

                $this -> cookies -> add(
                    new Cookie( array( 'value' => $cookie ) )
                );

            } catch( FieldsException $e ) {

                /**
                 * @internal
                 * We'll rethrow the same Exception caught if a true error occur
                 * so our Exception Handler can do the rest
                 */
                if( $e -> getCode() !== FieldsException::ALL_INVALID ) {

                    throw FieldsException::invalidHeaderValue(

                        $e -> getMessage(), $e -> getCode()
                    );
                }
            }
        }

        return $this;
    }

    /**
     * Get registered Cookies
     *
     * @param boolean $asString
     *  If TRUE, instead a Collection, a string of all the cookies will be returned
     *
     * @return \Next\Components\Collections\Lists|\Next\HTTP\Headers\Fields\Request\Cookie
     *
     *   <p>
     *     If <strong>$asString</strong> is set to FALSE, the Cookies
     *     Lists Collection will be returned
     *   </p>
     *
     *   <p>
     *     If <strong>$asString</strong> is TRUE, a well formed Request
     *     Cookie Header will be returned
     *   </p>
     */
    public function getCookies( $asString = FALSE ) {

        if( $asString === FALSE ) {
            return $this -> cookies -> getCollection();
        }

        // Is there something to return?

        if( $this -> cookies -> count() == 0 ) {
            return NULL;
        }

        // Let's return as string

        $cookieString = NULL;

        $iterator = $this -> cookies -> getIterator();

        iterator_apply(

            $iterator,

            function( \Iterator $iterator ) use( &$cookieString ) {

                $cookieString .= sprintf( "%s;", $iterator -> current() -> getValue() );

                return TRUE;
            },

            array( $iterator )
        );

        return new Cookie( array( 'value' => rtrim( $cookieString, ";" ) ) );
    }

    /**
     * Empties Request Cookies
     *
     * @return \Next\HTTP\Cookies
     *  Cookies Object (Fluent Interface)
     */
    public function clearCookies() {

        $this -> cookies -> clear();

        return $this;
    }
}
