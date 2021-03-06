<?php

/**
 * HTTP Response Header Field Class: Set-Cookie | HTTP\Headers\Response\Set-Cookie.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Response;

use Next\Validation\Validator as Validators;    # Validators Interface
use Next\HTTP\Headers\Field;                    # Header Field Abstract Class

/**
 * Response Header Field Validation Class: 'Set-Cookie'
 */
use Next\Validation\HTTP\Headers\Response\SetCookie as Validator;

/**
 * Response Header Field: 'Set-Cookie'
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Validation\Validator
 *             Next\HTTP\Headers\Field
 *             Next\Validation\HTTP\Headers\SetCookie
 */
class SetCookie extends Field {

    // Methods Overwritten

    /**
     * PRE Check Routines
     *
     * Adding quotes around params
     * in according to RFC 2109 Section 4.2.2
     *
     * Also try to fix some structure problems in Cookies string
     *
     * @param string $data
     *  Data to manipulate before validation
     *
     * @return string
     *  Data to Validate
     */
    protected function preCheck( $data ) : string {

        // Repetitive, but fixes one of the most crazy Set-Cookie tested so far...

        $data = preg_replace( '/([^;]+)\=([^;]+)(;)/', '\\1="\\2"\\3', $data );
        $data = preg_replace( '/([^;]+)\=([^;]+)( (?=[^;]+\=))/', '\\1="\\2";\\3', $data );

        /**
         * @internal
         * The separator used in Set-Cookie Header, in according to
         * RFC 2616 Section 14.18 (Date), is the same of the first
         * non-alpha character in Cookie Expiration, a comma ( , ).
         *
         * For that reason we'll replace the header separator by something else,
         * called control char, so our splitting routine can work properly
         *
         * We'll try to select the most uncommon character to do so.
         *
         * This grants compatibility with "old" servers, which send semicolon
         * separated values, and with "new" ones, which use the comma-separated notation
         */
        $data = preg_replace( '/"(?:,|;) \b/', '"; ', $data );

        /**
         * @internal
         * After flags we'll add a special char, so we can fix
         * RFC 2109 comma-separated structure later
         */
        $data = preg_replace( '/(secure|httponly)[^;]\s*/', '\\1~ ', $data );

        /**
         * @internal
         * If the last Cookie doesn't follows the same semi-colon separated structure
         * we'll add our control char before it...
         */
        $data = preg_replace( '/((version|comment|expires|max-age|path|domain|secure|httponly)=[^~;]+;\s*)(?!version|comment|expires|max-age|path|domain|secure|httponly)/', '\\1~\\3', $data );

        // But, at least so far, we have correctable a side-effect

        return strtr( $data, [ ';~' => '; ~', ';'  => '~ ' ] );
    }

    /**
     * POST Check Routines
     *
     * Changes the Multiple separator in runtime, after checking process
     *
     * @param string $data
     *  Data to manipulate after validation
     *
     * @return string
     *  Input data untouched
     */
    protected function postCheck( $data ) : string {

        $this -> options -> multiplesSeparator = ',';

        return $data; // Untouched
    }

    // Abstract Methods Implementation

    /**
     * Get Header Field Validator
     *
     * @param mixed|string $value
     *  Header value to be validated
     *
     * @return \Next\Validation\Validator
     *  Associated Validator
     */
    protected function getValidator( $value ) : Validators {
        return new Validator( [ 'value' => $value ] );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() : array {
        return [ 'name' => 'Set-Cookie', 'acceptMultiples' => TRUE, 'multiplesSeparator'    => '~' ];
    }
}
