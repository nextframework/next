<?php

/**
 * File Upload with Method 'POST' Class | File\Upload\Methods\Post.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\File\Upload\Methods;

use Next\File\Upload\UploadException;    # Upload Exception Class

use Next\Components\Utils\ArrayUtils;    # Array Utils Class
use Next\File\Tools;                     # File Tools Class

use Next\Validate\File\Size;             # File Size Validation Class
use Next\Validate\File\Extension;        # File Extensions Validation Class
use Next\Validate\File\MimeType;         # File Type Validation Class

/**
 * Defines a File Uploading through different POST Request methods
 *
 * @package    Next\File\Upload
 */
class Post extends AbstractMethod {

    /**
     * Error Messages
     *
     * @var array $errorMessages
     */
    private $errorMessages = array(

        'UNKNOWN'                 => 'Unknown upload error',

        // PHP File Upload Errors

        UPLOAD_ERR_INI_SIZE       => 'The uploaded file exceeds the <strong>upload_max_filesize</strong> directive in php.ini',
        UPLOAD_ERR_FORM_SIZE      => 'The uploaded file exceeds the <strong>MAX_FILE_SIZE</strong> directive that was specified in the HTML form',
        UPLOAD_ERR_PARTIAL        => 'The uploaded file was only partially uploaded',
        UPLOAD_ERR_NO_FILE        => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR     => 'Missing a temporary folder',
        UPLOAD_ERR_CANT_WRITE     => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION      => 'File upload stopped by extension',

        // Throwable Errors

        'uploadFailure'           => 'Upload failed'
    );

    /**
     * Additional initialization
     * Add default File Upload Validators
     */
    protected function init() {

        $options = $this -> handler -> getOptions();

        // Adding Basic Validators

        $this -> validators -> add( new      Size( $options ) )
                            -> add( new Extension( $options ) )
                            -> add( new  MimeType( $options ) );
    }

    // Method Interface Method

    /**
     * Handles the uploading process
     *
     * @return \Next\File\Upload\Methods\Post
     *  Post Object (Fluent-Interface)
     */
    public function handle() {

        $options = $this -> handler -> getOptions();

        if( ! array_key_exists( $options -> name, $_FILES ) ) {
            throw UploadException::nothingToUpload();
        }

        $files = $_FILES[ $options -> name ];

        // Normalizing single and multiple uploads structure

        if( reset( $files ) !== FALSE && ! is_array( $files[ key( $files ) ] ) ) {

            $files = array( $files );

        } else {

            $files = ArrayUtils::transpose( $files );
        }

        // JavaScript-based File Uploads usually ignores this

        if( count( $files ) > $options -> maxConcurrentFiles ) {
            throw UploadException::concurrentFilesLimit();
        }

        foreach( $files as $file ) {

            /**
             * @internal
             *
             * array_values() is used because when only one file is uploaded at a time
             * $_FILES superglobal will not be transposed while iterating and $file will
             * be an associative array which does not work very well with list()
             */
            list( $name, $type, $temp, $error, $size ) = array_values( $file );

            if( $error == UPLOAD_ERR_OK ) {

                $this -> proccess( $name, $size, $type, $temp );

            } else {

                $errOffset = ( array_key_exists( $error, $this -> errorMessages ) ? $error : 'UNKNOWN' );

                $this -> failed = array(

                    'name' => $name, 'size' => Tools::readableFilesize( $size ), 'type' => $type,

                    'reason' => $this -> errorMessages[ $errOffset ],
                );
            }
        }

        return $this;
    }

    // Auxiliary Methods

    /**
     * Process successfully uploaded files
     *
     * @param string $file
     *  File name
     *
     * @param integer $size
     *   File size
     *
     * @param string $type
     *  File type
     *
     * @param string $temp
     *  Path of temporary file
     *
     * @return array
     *  Array with successfully uploaded data
     */
    protected function proccess( $file, $size, $type, $temp ) {

        // Fixing common problems

        $this -> fix( $file, $size, $type, $temp );

        $readableFilesize = Tools::readableFilesize( $size );

        // Validating Uploaded File

        $validation = $this -> validators -> validate( array( $file, $size, $type, $temp ) );

        if( $validation !== TRUE ) {

            $this -> failed[] = array(

                'name' => $file, 'size' => $readableFilesize, 'type' => $type,

                'reason' => $validation -> getErrorMessage()
            );

            return;
        }

        // Uploading...

        $uploadFile = sprintf( '%s/%s', $this -> handler -> getOptions() -> uploadDir, $file );

        if( move_uploaded_file( $temp, $uploadFile ) ) {

            // Performing tasks after the file is effectively uploaded

            $postProcess = $this -> postProcessors -> process( $uploadFile );

            if( $postProcess !== TRUE ) {

                $this -> failed[] = array(

                    'name' => $file, 'size' => $readableFilesize, 'type' => $type,

                    'reason' => $postProcess -> getErrorMessage()
                );

                return;

            } else {

                $this -> succeed[] = array(

                    'name' => $file, 'type' => $type, 'size' => $readableFilesize
                );
            }

        } else {

            $this -> failed[] = array(

                'name' => $file, 'size' => $readableFilesize, 'type' => $type,

                'reason' => $this -> errorMessages['uploadFailure']
            );
        }
    }

    /**
     * Fix common problems with informations received after upload
     *
     * @param string $file
     *  File name
     *
     * @param integer $size
     *   File size
     *
     * @param string $type
     *  File type
     *
     * @param string $temp
     *  Path of temporary file
     *
     * @return void
     */
    protected function fix( &$file, &$size, &$type, &$temp ) {

        /**
         * Integer Overflow
         *
         * Fix overflowing signed 32bit integers.
         * Works for sizes up to 2 ^ 32 -1 bytes (4 GB - 1)
         */
        if( $size < 0 ) $size += 2.0 * ( PHP_INT_MAX + 1 );

        $info = new \finfo;

        /**
         * MIME-Type
         *
         * The MIME-Type of uploaded files are provided by browser and not
         * automatically validated by PHP, so if there is anything wrong with
         * the way the browsers perform this detection, like a 3rd-party software
         * injecting an invalid entry in Firefox mimeTypes.rdf file, this
         * information becomes not reliable
         */
        $type = $info -> file( $temp, \FILEINFO_MIME_TYPE );
    }
}