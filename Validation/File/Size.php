<?php

/**
 * Filesize Validator Class | Validation\File\Size.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\File;

use Next\Validation\Validator;    # Validator Interface
use Next\Components\Object;       # Object Class

/**
 * The File Size Validator checks if given File Size is accepted
 * towards maximum size for a File
 *
 * @package    Next\Validation
 *
 * @uses      Next\Validation\Validator
 *            Next\Components\Object
 */
class Size extends Object implements Validator {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value'       => [ 'required' => TRUE ],
        'maxFileSize' => [ 'required' => TRUE ]
    ];

    /**
     * Error Message
     *
     * @var string $_error
     */
    protected $_error = 'Maximum file size exceeded';

    // Validator Interface Methods

    /**
     * Validates given File Size
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() : bool {
        return ( (int) $this -> options -> value < $this -> options -> maxFileSize );
    }
}
