<?php

namespace Next\Components\Types;

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
     *   Value to set
     *
     * @return boolean
     *   TRUE if given value is of the type string and FALSE otherwise
     */
    protected function accept( $value ) {
        return gettype( $value ) == 'string';
    }

    /**
     * Prototype resources to object
     *
     * @param mixed|optional $value
     *   An optional value to be used by prototyped resource
     */
    protected function prototype( $s = NULL ) {

        // All prototypes require a value

        if( is_null( $s ) ) {
            return;
        }

        $this -> implement( 'compare',        'strcmp',         $s )
        	  -> implement( 'caseCompare',    'strcasecmp',     $s )
        	  -> implement( 'explode',        'explode',        array( 1 => $s ) )
        	  -> implement( 'find',           'strstr',         $s )
        	  -> implement( 'lowerFirst',     'lcfirst',        $s )
        	  -> implement( 'pad',            'str_pad',        $s )
        	  -> implement( 'repeat',         'str_repeat',     $s )
        	  -> implement( 'replace',        'str_replace',    array( 2 => $s ) )
        	  -> implement( 'reverseFind',    'strrpos',        $s )
        	  -> implement( 'shuffle',        'str_shuffle',    $s )
        	  -> implement( 'striptags',      'strip_tags',     $s )
        	  -> implement( 'substr',         'substr',         $s )
        	  -> implement( 'toLower',        'strtolower',     $s )
        	  -> implement( 'toUpper',        'strtoupper',     $s )
        	  -> implement( 'trim',           'trim',           $s )
        	  -> implement( 'upperFirst',     'ucfirst',        $s )
        	  -> implement( 'upperWords',     'ucwords',        $s );
    }
}