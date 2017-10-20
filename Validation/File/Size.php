<?php

/**
 * Filesize Validator Class | Validate\File\Size.php
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
 * File Size Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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
    public function validate() {
        return ( (int) $this -> options -> value < $this -> options -> maxFileSize );
    }
}