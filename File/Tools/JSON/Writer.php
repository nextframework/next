<?php

/**
 * JSON Writer Class | File\Tools\JSON\Writer.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\File\Tools\JSON;

use Next\Components\Debug\Exception;            # Exception Class

use Next\Components\Object;                     # Object Class

use Next\HTTP\Stream\Writer as StreamWriter;    # HTTP Stream Writer Class
use Next\HTTP\Stream\Adapter\Socket;            # HTTP Stream Socket Class

use Next\File\Tools;                            # File Tools Class

/**
 * Defines a wrapper for JSON writing
 *
 * @package    Next\File\Tools\JSON
 */
class writer extends Object {

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
     * JSON Data
     *
     * @var string $data
     */
    private $data;

    /**
     * JSON Writer Wrapper COnstructor
     *
     * @param string $file
     *  JSON File to write
     *
     * @param string|array $data
     *  Data to write
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for the JSON Writer
     *
     * @throws \Next\Components\Debug\Exception
     *  Thrown if given data is a valid PHP resource
     */
    public function __construct( $file, $data, $options = NULL ) {

        if( is_resource( $data ) ) {
            throw new Exception( 'Resources cannot be encoded' );
        }

        $this -> file = $file;

        $this -> data = $data;
    }

    /**
     * Writes the JSON File
     *
     * @param integer|optional $options
     *  Additional options to be passed to json_encode()
     *
     * @return void
     */
    public function write( $options = 0 ) {

        $writer = new StreamWriter( new Socket( $this -> file, 'c' ) );

        $content = json_encode( $this -> data, $options );

        if( $content === FALSE ) {

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

        $bytes = $writer -> write( $content );

        return $bytes;
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