<?php

/**
 * File MIME-Type Validator Class | Validate\File\MIME.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validate\File;

use Next\Validate\Validator;             # Validator Interface

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
class MimeType extends Object implements Validator {

    /**
     * Error Message
     *
     * @var string $_error
     */
    protected $_error = 'Invalid file type';

    // Validator Interface Methods

    /**
     * Validates given File MimeType
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        $data = $this -> options -> value;

        if( count( (array) $this -> options -> acceptedFileTypes ) == 0 ) return TRUE;

        $test = ArrayUtils::search(
            (array) $this -> options -> acceptedFileTypes, $data
        );

        return ( $test != -1 );
    }

    // Parameterizable Interface Method Overriding

    /**
     * Set Class Options.
     * Defines which Parameter Options are known by the Validator Class
     *
     * @return array
     *  An array with Custom Options overriding or complementing Object defaults
     */
    public function setOptions() {
        return [ 'value' => [ 'required' => TRUE ] ];
    }
}
