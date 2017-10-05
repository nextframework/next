<?php

/**
 * Types Component "String" Type Class | Components\Types\String.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Types;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

/**
 * Defines the String Data-type Type and prototypes some o PHP String
 * functions (some in different argument order) and some external/custom
 * resources as well
 *
 * @package    Next\Components\Types
 */
class String extends AbstractTypes {

    /**
     * Truncate Prototyped resource controlling constants
     *
     * @var string
     */
    const TRUNCATE_BEFORE = 1;
    const TRUNCATE_AFTER  = 2;
    const TRUNCATE_CENTER = 3;
    const TRUNCATE_DEFAULT_REPLACEMENT = '...';

    /**
     * String Padding resource controlling constants
     *
     * @var string
     */
    const PAD_LEFT  = 0;
    const PAD_RIGHT = 1;
    const PAD_BOTH  = 2;

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity.
     * Checks whether or not given value is acceptable by data-type class
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'value' is not a string -OR- is NULL
     */
    public function verify() {

        if( is_null( $this -> options -> value ) || ! is_string( $this -> options -> value ) ) {

            throw new InvalidArgumentException(
                'Argument is not a valid String'
            );
        }
    }

    // Prototypable Method Implementation

    /**
     * Prototypes a custom, and maybe complex, routine to one of
     * the available types by proxying, treating and handling
     * the mixed arguments received
     */
    public function prototype() {

        // Native Functions

        $this -> implement( $this, 'compare',        'strcmp',         [ 0 => $this -> _value ] )
              -> implement( $this, 'caseCompare',    'strcasecmp',     [ 0 => $this -> _value ] )
              -> implement( $this, 'lowerFirst',     'lcfirst',        [ 1 => $this -> _value ] )
              -> implement( $this, 'find',           'strstr',         $this -> _value )
              -> implement( $this, 'repeat',         'str_repeat',     $this -> _value )
              -> implement( $this, 'reverseFind',    'strrpos',        $this -> _value )
              -> implement( $this, 'reverse',        'strrev',         $this -> _value )
              -> implement( $this, 'shuffle',        'str_shuffle',    $this -> _value )
              -> implement( $this, 'striptags',      'strip_tags',     $this -> _value )
              -> implement( $this, 'substring',      'substr',         $this -> _value )
              -> implement( $this, 'toLower',        'strtolower',     $this -> _value )
              -> implement( $this, 'toUpper',        'strtoupper',     $this -> _value )
              -> implement( $this, 'trim',           'trim',           $this -> _value )
              -> implement( $this, 'trimLeft',       'ltrim',          $this -> _value )
              -> implement( $this, 'trimRight',      'rtrim',          $this -> _value )
              -> implement( $this, 'upperFirst',     'ucfirst',        $this -> _value )
              -> implement( $this, 'upperWords',     'ucwords',        $this -> _value );

            /**
             * @internal
             *
             * Because some of PHP functions have a not so obvious argument
             * order using our Argument Swapping to put `$this -> _value`
             * directly in the position the original function expect the
             * input string to be works for instanced calls, but not work
             * for static calls because the duplication removal done in
             * \Next\Components\Prototype::call()
             *
             * So for these cases to work regardless how the Prototype is
             * invoked we need to keep the value in the first position —
             * thus invalidating the argument swapping — -AND- make a
             * "Closured" implementation
             *
             * @see \Next\Components\Prototype::call()
             */

            // explode()

        $this -> implement( $this, 'explode', function( $string, $delimiter, $limit = PHP_INT_MAX ) {

            return explode( $delimiter, $string, $limit );

        }, [ 0 => $this -> _value ] );

            // str_replace()

        $this -> implement( $this, 'replace', function( $string, $search, $replacement, &$count = NULL ) {

            return str_replace( $search, $replacement, $string, $count );

        }, [ 0 => $this -> _value ] );

        // Custom Prototypes

        $this -> implement( $this, 'pad',      new String\Pad,       $this -> _value )
              -> implement( $this, 'truncate', new String\Truncate,  $this -> _value )
              -> implement( $this, 'GUID',     new String\GUID )
              -> implement( $this, 'AlphaID',  new String\AlphaID,   $this -> _value );

        /**
         * Quotes a string with given Quote Identifier
         *
         * @param string $string
         *  The string to be quoted. Not directly passed!
         *
         * @param string|optional $identifier
         *  The Quote Identifier that'll wrap the input string
         *
         * @return Next\Components\Types\String
         *  A String Data-type Object with the quoted string
         */
        $this -> implement( $this, 'quote', function( $string, $identifier = '"' ) {

            return new String(
                [ 'value' => sprintf( '%s%s%s', $identifier, $string, $identifier ) ]
            );

        }, $this -> _value );
    }
}