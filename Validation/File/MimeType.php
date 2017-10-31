<?php

/**
 * File MIME-Type Validator Class | Validation\File\MIME.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\File;

use Next\Validation\Validator;           # Validator Interface
use Next\Components\Object;              # Object Class
use Next\Components\Utils\ArrayUtils;    # Array Utils Class

/**
 * The File MIME-Type Validator checks if given MIME-Type is accepted
 * towards a predefined set of MIME-Types
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\Validator
 *             Next\Components\Object
 *             Next\Components\Utils\ArrayUtils
 */
class MimeType extends Object implements Validator {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value'             => [ 'required' => TRUE ],
        'acceptedFileTypes' => [ 'required' => FALSE, 'default' => [] ]
    ];

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
    public function validate() : bool {

        if( count( (array) $this -> options -> acceptedFileTypes ) == 0 ) return TRUE;

        $test = ArrayUtils::search(
            (array) $this -> options -> acceptedFileTypes, $this -> options -> value
        );

        return ( $test != -1 );
    }
}
