<?php

namespace Next\Validate\File;

use Next\Validate\Validate;              # Validate Interface

use Next\Components\Object;              # Object Class

use Next\Components\Utils\ArrayUtils;    # Array Utils Class
use Next\File\Tools;                     # File Tools Class

/**
 * File Extensions Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Extension extends Object implements Validate {

    /**
     * Error Message
     *
     * @var string $errorMessage
     */
    protected $errorMessage = 'Invalid file extension';

    // Validate Interface Methods

    /**
     * Validates given File Extension
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        $data = $this -> options -> value;

        if( count( (array) $this -> options -> acceptedFileExtensions ) == 0 ) {
            return TRUE;
        }

        $ext = ( is_array( $data ) ? $data[ 0 ] : $data );

        return ArrayUtils::in(

            Tools::getFileExtension( $ext ),

            (array) $this -> options -> acceptedFileExtensions
        );
    }
}
