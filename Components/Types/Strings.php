<?php

/**
 * Types Component "Strings" Type Class | Components\Types\Strings.php
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
 * The Strings Data-type with prototypes of external/custom resources
 *
 * @package    Next\Components\Types
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Types\AbstractType
 *             Next\Components\Types\Strings\Pad
 *             Next\Components\Types\Strings\Truncate
 *             Next\Components\Types\Strings\GUID
 *             Next\Components\Types\Strings\AlphaID
 */
class Strings extends AbstractTypes {

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity.
     * Checks whether or not given value is acceptable by data-type class
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'value' is not a string -OR- is NULL
     */
    public function verify() : void {

        if( $this -> options -> value === NULL || ! is_string( $this -> options -> value ) ) {

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
    public function prototype() : void {

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

        // Custom/Adapted Prototypes

        $this -> implement( $this, 'explode',  'Next\Components\Types\Strings\Explode',  $this -> _value )
              -> implement( $this, 'replace',  'Next\Components\Types\Strings\Replace',  $this -> _value )
              -> implement( $this, 'quote',    'Next\Components\Types\Strings\Quote',    $this -> _value )
              -> implement( $this, 'pad',      'Next\Components\Types\Strings\Pad',      $this -> _value )
              -> implement( $this, 'truncate', 'Next\Components\Types\Strings\Truncate', $this -> _value )
              -> implement( $this, 'GUID',     'Next\Components\Types\Strings\GUID' )
              -> implement( $this, 'AlphaID',  'Next\Components\Types\Strings\AlphaID',  $this -> _value );
    }
}