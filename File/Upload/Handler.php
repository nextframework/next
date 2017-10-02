<?php

/**
 * File Upload Handler Class | File\Upload\Handler.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\File\Upload;

use Next\Application\Application;                  # Application Interface

use Next\Components\Object;                        # Object Class
use Next\Components\Parameter;                     # Parameter Class

use Next\HTTP\Request;                             # Request Class
use Next\HTTP\Response;                            # Response Class

/**
 * Defines a File Upload Handler delegating the process accordingly
 * to the Request Method
 *
 * @package    Next\File\Upload
 */
class Handler extends Object {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        'uploadDir'                 => 'uploads',
        'name'                      => 'files',
        'acceptedFileExtensions'    => [],           // All extensions allowed
        'acceptedFileTypes'         => [],           // All filetypes allowed
        'maxFileSize'               => 1000000,      // 1MB
        'maxConcurrentFiles'        => 2
    ];

    /**
     * Request Object
     *
     * @var \Next\HTTP\Request
     */
    private $request;

    /**
     * Response Object
     *
     * @var \Next\HTTP\Response
     */
    private $response;

    /**
     * Upload Handler Constructor
     *
     * @param \Next\Application\Application|optional $application
     *  Optional Application Object
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for Upload Handler
     *
     * @see \Next\Components\Parameter
     */
    public function __construct( Application $application = NULL, $options = NULL ) {

        parent::__construct( $options );

        $this -> request     = $application -> getRequest();
        $this -> response    = $application -> getResponse();

        $this -> checkIntegrity();
    }

    /**
     * Handle the upload process accordingly with Request Method
     *
     * @return \Next\File\Upload\Methods\Method
     *  Upload Method
     */
    public function upload() {

        $method = $this -> request -> getData( $_REQUEST, '_method' );

        /**
         * @internal
         *
         * What?! o.O
         */
        /*if( ! is_null( $method ) && $method === Request::DELETE ) {
            return new Methods\Delete;
        }*/

        switch( $this -> request -> getServer( 'REQUEST_METHOD' ) ) {

            case Request::OPTIONS:
            case Request::HEAD:
                return new Methods\Head( $this );
                break;

            case Request::GET:
                return new Methods\Get( $this );
                break;

            case Request::PATCH:
            case Request::PUT:
            case Request::POST:
                return new Methods\Post( $this );
                break;

            case Request::DELETE:
                return new Methods\Delete( $this );
                break;

            default:
                $this -> response -> addHeader( 405 ) -> send();
            break;
        }
    }

    // Accessors

    /**
     * Get Request Object
     *
     * @return \Next\HTTP\Request
     *  Request Object
     */
    public function getRequest() {
        return $this -> request;
    }

    /**
     * Get Response Object
     *
     * @return \Next\HTTP\Response
     *  Response Object
     */
    public function getResponse() {
        return $this -> response;
    }

    // Auxiliary Methods

    /**
     * Checks the integrity of Parameterizable Object ensuring all options are set as they should
     *
     * @throws \Exception
     *  Thrown if defined Upload Directory is not a directory
     *
     * @throws \Exception
     *  Thrown if defined Upload Directory is not a writable
     */
    private function checkIntegrity() {

        if( ! is_dir( $this -> options -> uploadDir ) ) {

            throw new \Exception(
                sprintf( 'Upload directory %s is not a valid directory', $this -> options -> uploadDir )
            );
        }

        if( ! is_writable( $this -> options -> uploadDir ) ) {

            throw new \Exception(
                sprintf( 'Upload directory %s is not writable', $this -> options -> uploadDir )
            );
        }
    }
}