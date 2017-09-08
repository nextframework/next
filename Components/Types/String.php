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

use Next\Components\Types\String\AlphaID;    # AlphaID Prototype
use Next\Components\Types\String\GUID;       # GUID Prototype Class
use Next\Components\Types\String\Truncate;   # Truncate String Prototype Class

/**
 * Defines the String Data-type Type and prototypes some o PHP String
 * functions (some in different argument order) and some external/custom
 * resources as well
 *
 * @package    Next\Components\Types
 */
final class String extends AbstractTypes {

    /**
     * Truncate Prototyped resource controlling constants
     *
     * @var string
     */
    const TRUNCATE_BEFORE = 1;
    const TRUNCATE_AFTER  = 2;
    const TRUNCATE_CENTER = 3;
    const TRUNCATE_DEFAULT_REPLACEMENT = '...';

    // Abstract Methods Implementation

    /**
     * Check whether or not given value is acceptable by datatype class
     *
     * @param mixed $value
     *  Value to set
     *
     * @return boolean
     *  TRUE if given value is of the type string and FALSE otherwise
     */
    protected function accept( $value ) {
        return ( ! is_null( $value ) && is_string( $value ) );
    }

    // Prototypable Method Implementation

    /**
     * Prototypes a custom, and maybe complex, routine to one of
     * the available types by proxying, treating and handling
     * the mixed arguments received
     */
    public function prototype() {

        // Prototypes that doesn't require an initial base value to work with

        $this -> implement( 'GUID', new GUID );

        // Prototypes that requires a value to work with

        if( $this -> _value !== NULL ) {

            // Native Functions

            $this -> implement( 'compare',        'strcmp'          )
                  -> implement( 'caseCompare',    'strcasecmp'      )
                  -> implement( 'explode',        'explode',        array( 1 => $this -> _value ) )
                  -> implement( 'find',           'strstr'          )
                  -> implement( 'lowerFirst',     'lcfirst'         )
                  -> implement( 'pad',            'str_pad'         )
                  -> implement( 'repeat',         'str_repeat'      )
                  -> implement( 'replace',        'str_replace',    array( 2 => $this -> _value ) )
                  -> implement( 'reverseFind',    'strrpos'         )
                  -> implement( 'shuffle',        'str_shuffle'     )
                  -> implement( 'striptags',      'strip_tags'      )
                  -> implement( 'substr',         'substr'          )
                  -> implement( 'toLower',        'strtolower'      )
                  -> implement( 'toUpper',        'strtoupper'      )
                  -> implement( 'trim',           'trim'            )
                  -> implement( 'upperFirst',     'ucfirst'         )
                  -> implement( 'upperWords',     'ucwords'         );

            // Custom Prototypes

            $this -> implement( 'alphaID',  new AlphaID,  $this -> _value )
                  -> implement( 'truncate', new Truncate, $this -> _value );

            $this -> implement( 'getFileExtension', function() {

                return new String( pathinfo( func_get_arg( 0 ), PATHINFO_EXTENSION ) );

                return ( ! empty( $extension ) ? $extension : FALSE );

            }, $this -> value);
        }
    }
}