<?php

namespace Next\Components\Types;

use Next\Components\Types\String\AlphaID;    # AlphaID Prototype
use Next\Components\Types\String\GUID;       # GUID Prototype Class

/**
 * String Datatype Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
final class String extends AbstractTypes {

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
        return is_string( $value );
    }

    /**
     * Prototype resources to object
     *
     * @return void
     */
    protected function prototype() {

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

            $this -> implement( 'alphaID', new AlphaID, $this -> _value );
        }
    }
}