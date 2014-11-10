<?php

namespace Next\Validate\File;

use Next\Validate\Validate;              # Validate Interface

use Next\Components\Object;              # Object Class

use Next\Components\Utils\ArrayUtils;    # Array Utils Class

/**
 * File MimeType Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class MimeType extends Object implements Validate {

    /**
     * Error Message
     *
     * @var string $errorMessage
     */
    protected $errorMessage = 'Invalid file type';

    // Validate Interface Methods

    /**
     * Validates given File MimeType
     *
     * @param string|array $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate( $data ) {

        if( count( (array) $this -> options -> acceptedFileTypes ) == 0 ) return TRUE;

        $type = ( is_array( $data ) ? $data[ 2 ] : $data );

        return ( ArrayUtils::in( $type, (array) $this -> options -> acceptedFileTypes ) );
    }
}
