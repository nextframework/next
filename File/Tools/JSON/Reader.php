<?php

/**
 * JSON Reader Class | File\Tools\JSON\Reader.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\File\Tools\JSON;

use Next\Components\Debug\Exception;            # Exception Class

use Next\Components\Object;                     # Object Class

use Next\HTTP\Stream\Reader as StreamReader;    # HTTP Stream Reader Class
use Next\HTTP\Stream\Adapter\Socket;            # HTTP Stream Socket Class

/**
 * Defines a wrapper for JSON reading
 *
 * @package    Next\File\Tools\JSON
 */
class Reader extends Object {

    const ERR_JSON_ERROR_NONE              = '';
    const ERR_JSON_ERROR_DEPTH             = 'Maximum stack depth exceeded';
    const ERR_JSON_ERROR_STATE_MISMATCH    = 'Underflow or the modes mismatch';
    const ERR_JSON_ERROR_CTRL_CHAR         = 'Unexpected control character found';
    const ERR_JSON_ERROR_SYNTAX            = 'Syntax error, malformed JSON';
    const ERR_JSON_ERROR_UTF8              = 'Malformed UTF-8 characters, possibly incorrectly encoded';

    /**
     * JSON File
     *
     * @var string $file
     */
    private $file;

    /**
     * JSON Reader Wrapper Constructor
     *
     * @param string $file
     *   JSON File to read
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for the JSON Reader
     */
    public function __construct( $file, $options = NULL ) {

        parent::__construct( $options );

        $this -> file = $file;
    }

    /**
     * Reads the JSON File
     *
     * @param boolean|optional $assoc
     *  Defines whether or not the processed data will be returned
     *  as associative array. Defaults to TRUE
     *
     * @return array|stdClass
     *  A multidimensional array with passed data or an stdClass object
     *  if given argument is set to FALSE
     */
    public function read( $assoc = TRUE ) {

        $reader = new StreamReader( new Socket( $this -> file ) );

        $buffer = $reader -> readAll();

        $content = json_decode( $buffer, $assoc );

        if( is_null( $content )  ) {

            throw new Exception(

                vsprintf(

                    '<p>
                        <strong>Code:</strong> %d
                    </p>

                    <p>
                        <strong>Message:</strong> %s
                    </p>',

                    $this -> errorToString( json_last_error() )
                )
            );
        }

        return $content;
    }

    // Auxiliary Methods

    /**
     * Converts json_last_error() to a human-readable string, pretty much like
     * json_last_error_msg(), but for older versions than PHP 5.5+
     *
     * @param integer $error
     *  The error code returned by json_last_error()
     *
     * @return array
     *  A two-indexes array with the error code and a human-readable message
     */
    private function errorToString( $error ) {

        switch( $error ) {

            case JSON_ERROR_DEPTH:          $msg = self::ERR_JSON_ERROR_DEPTH;          break;
            case JSON_ERROR_STATE_MISMATCH: $msg = self::ERR_JSON_ERROR_STATE_MISMATCH; break;
            case JSON_ERROR_CTRL_CHAR:      $msg = self::ERR_JSON_ERROR_CTRL_CHAR;      break;
            case JSON_ERROR_SYNTAX:         $msg = self::ERR_JSON_ERROR_SYNTAX;         break;
            case JSON_ERROR_UTF8:           $msg = self::ERR_JSON_ERROR_UTF8;           break;
            default:                        $msg = self::ERR_JSON_ERROR_NONE;           break;
        }

        return array( $error, $msg );
    }
}