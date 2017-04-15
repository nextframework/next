<?php

namespace Next\Validate\File;

use Next\Validate\Validate;    # Validate Interface

use Next\Components\Object;    # Object Class

/**
 * File Size Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Size extends Object implements Validate {

    /**
     * Error Message
     *
     * @var string $errorMessage
     */
    protected $errorMessage = 'Maximum file size exceeded';

    // Validate Interface Methods

    /**
     * Validates given File Size
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        $data = $this -> options -> value;

        $size = ( is_array( $data ) ? $data[ 1 ] : $data );

        return ( (int) $size < $this -> options -> maxFileSize );
    }
}
