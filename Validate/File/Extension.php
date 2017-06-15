<?php

/**
 * File Extension Validator Class | Validate\File\Extension.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Validate\File;

use Next\Validate\Validator;             # Validator Interface

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
class Extension extends Object implements Validator {

    /**
     * Error Message
     *
     * @var string $_error
     */
    protected $_error = 'Invalid file extension';

    // Validator Interface Methods

    /**
     * Validates given File Extension
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        if( count( (array) $this -> options -> acceptedFileExtensions ) == 0 ) {
            return TRUE;
        }

        $test = ArrayUtils::search(

            (array) $this -> options -> acceptedFileExtensions,

            Tools::getFileExtension( $this -> options -> value )
        );

        return ( $test != -1 );
    }
}
