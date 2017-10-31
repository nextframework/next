<?php

/**
 * File Extension Validator Class | Validation\File\Extension.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\File;

use Next\Validation\Validator;           # Validator Interface
use Next\Components\Object;              # Object Class
use Next\FileSystem\Path;                # FileSystem Path Data-type Class
use Next\Components\Utils\ArrayUtils;    # Array Utils Class

/**
 * The File Extension Validator checks if given File Extension is accepted
 * towards a predefined set of Extensions
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\Validator
 *             Next\Components\Object
 *             Next\FileSystem\Path
 *             Next\Components\Utils\ArrayUtils
 */
class Extension extends Object implements Validator {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value'                  => [ 'required' => TRUE ],
        'acceptedFileExtensions' => [ 'required' => FALSE, 'default' => [] ]
    ];

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
    public function validate() : bool {

        if( count( (array) $this -> options -> acceptedFileExtensions ) == 0 ) {
            return TRUE;
        }

        $extension = new Path(
            [ 'value' => $this -> options -> value ]
        );

        // Unable to find an extension

        if( $extension === NULL ) return FALSE;

        $test = ArrayUtils::search(

            (array) $this -> options -> acceptedFileExtensions,

            $extension -> getExtension() -> get()
        );

        return ( $test != -1 );
    }
}
