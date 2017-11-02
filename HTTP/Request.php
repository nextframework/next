<?php

/**
 * HTTP Request Class | HTTP\Request.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP;

/**
 * Exception Class(es)
 */
use Next\Exception\Exception;
use Next\Exception\Exceptions\BadMethodCallException;
use Next\Exception\Exceptions\LengthException;
use Next\Exception\Exceptions\RuntimeException;

use Next\Validation\Verifiable;                 # Verifiable Interface
use Next\Components\Interfaces\Configurable;    # Configurable Interface
use Next\HTTP\Headers\Field;                    # Header Fields Interface
use Next\HTTP\Stream\Adapter\Adapter;           # HTTP Stream Adapter Interface

use Next\Components\Object;                     # Object Class
use Next\FileSystem\Path;                       # FileSystem Path Data-type Class
use Next\Components\Parameter;                  # Parameter Object
use Next\Components\Invoker;                    # Invoker Class
use Next\HTTP\Stream\Adapter\Socket;            # HTTP Stream Socket Adapter Class
use Next\HTTP\Stream\Context\SocketContext;     # HTTP Stream Socket Context Class
use Next\HTTP\Headers\Entity\ContentType;       # Content-Type Header Class
use Next\HTTP\Stream\Reader;                    # HTTP Stream Reader

/**
 * The Request Class
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Exception\Exception
 *             Next\Exception\Exceptions\BadMethodCallException
 *             Next\Validation\Verifiable
 *             Next\HTTP\Headers\Field
 *             Next\HTTP\Stream\Adapter\Adapter
 *             Next\Components\Object
 *             Next\Components\Interfaces\Configurable
 *             Next\FileSystem\Path
 *             Next\Components\Parameter
 *             Next\Components\Invoker
 *             Next\HTTP\Stream\Adapter\Socket
 *             Next\HTTP\Stream\Context\SocketContext
 *             Next\HTTP\Headers\Entity\ContentType
 *             Next\HTTP\Stream\Reader
 *             Next\HTTP\Request\Browser
 *             stdcLass
 */
class Request extends Object implements Configurable {

    // Request Method Constants

    /**
     * GET Method
     *
     * @var string
     */
    const GET             = 'GET';

    /**
     * PATCH Method
     *
     * @var string
     */
    const PATCH           = 'PATCH';

    /**
     * POST Method
     *
     * @var string
     */
    const POST            = 'POST';

    /**
     * PUT Method
     *
     * @var string
     */
    const PUT             = 'PUT';

    /**
     * DELETE Method
     *
     * @var string
     */
    const DELETE          = 'DELETE';

    /**
     * HEAD Method
     *
     * @var string
     */
    const HEAD            = 'HEAD';

    /**
     * OPTIONS Method
     *
     * @var string
     */
    const OPTIONS         = 'OPTIONS';

    /**
     * CONNECT Method
     *
     * @var string
     */
    const CONNECT         = 'CONNECT';

    /**
     * TRACE Method
     *
     * @var string
     */
    const TRACE           = 'TRACE';

    /**
     * HTTP Scheme
     *
     * @var string
     */
    const SCHEME_HTTP     = 'HTTP';

    /**
     * HTTPS Scheme
     *
     * @var string
     */
    const SCHEME_HTTPS    = 'HTTPS';

    /**
     * HTTP 1.0 Protocol
     *
     * @var float
     */
    const HTTP10          = 1.0;

    /**
     * HTTP 1.1 Protocol
     *
     * @var float
     */
    const HTTP11          = 1.0;

    // Protocol Detection Regexp

    /**
     * HTTP Protocol RegExp
     *
     * @var string
     */
    const PROTOCOL_REGEXP = '/(?<protocol>[^ \/;\t\n\r\f\v]+)\/(?<version>[1-9]+(?:\.[0-9]+)?)/';

    /**
     * Cookies Management Object
     *
     * @var \Next\HTTP\Cookies $cookies
     */
    private $cookies;

    /**
     * Headers Management Object
     *
     * @var \Next\HTTP\Headers\Manager $headers
     */
    private $headers;

    /**
     * HTTP Stream Adapter
     *
     * @var \Next\HTTP\Stream\Adapter\Adapter $adapter
     */
    private $adapter;

    /**
     * Request Host
     *
     * @var string $host
     */
    protected $host;

    /**
     * Request URI
     *
     * @var string $uri
     */
    protected $uri;

    /**
     * Request Method
     *
     * @var string $method
     */
    private $method;

    /**
     * Request Protocol
     *
     * @var stdClass $protocol
     */
    private $protocol;

    /**
     * Request Basepath
     *
     * @var string $basepath
     */
    private $basepath;

    /**
     * Query Data (a.k.a. GET Params)
     *
     * @var array $queryData
     */
    private $queryData = [];

    /**
     * POST Data (a.k.a. POST Params)
     *
     * @var array $postData
     */
    private $postData = [];

    /**
     * RAW POST Data (a.k.a. non URLEncoded POST Params)
     *
     * @var array $rawPostData
     */
    private $rawPostData = [];

    /**
     * Additional Initialization
     *
     * If only one argument is provided, it'll be checked against
     * \Next\HTTP\Stream\Adapter\Adapter.
     *
     * If it passes, it will be used and Request URI will be retrieved
     * from it
     *
     * Otherwise, this argument will be THE Request URI
     *
     * The second argument is the Request Method, also optional.
     *
     * If no arguments are entered (case of Routed Requests), default
     * values will take place: REQUEST_URI from $_SERVER and GET as
     * Request Method
     */
    protected function init() : void {

        // Cookies Management Object

        $this -> cookies = new Cookies;

        // Request Headers Management Object

        $this -> headers = new Headers\Manager;

        // Extend Object Context to Headers', Cookies and Browser Classes

        $this -> extend( new Invoker( $this, $this -> headers ) )
              -> extend( new Invoker( $this, $this -> cookies ) )
              -> extend( new Invoker( $this, new Request\Browser ) );

        // Initializing Data Properties with superglobals

        $this -> queryData   = $_GET;

        $this -> postData    = $_POST;

        //---------------------------

        // Setting Up Basic Informations

            // Do we have an HTTP Stream Adapter?

        if( $this -> options -> adapter !== NULL ) {

            $this -> adapter = $this -> options -> adapter;

            $this -> uri = $this -> options -> adapter -> getFilename();

        } else {

            // No? Then it'll be treated as an internal (routed) Request

            $this -> uri = $this -> options -> uri;
        }

            // Request Host

        $this -> host = $this -> options -> host;

            // Request Method

        $this -> method = strtoupper( $this -> options -> method );

        //---------------------------

        // Server Protocol

        $this -> protocol = new \stdClass;

        $data = parse_url( $this -> uri );

        if( array_key_exists( 'scheme', $data ) ) {

            // Protocol scheme

            $this -> protocol -> name = $data['scheme'];

            // URI Basepath

            /**
             * @internal
             * This implementation can be reliable because
             * an external request without HTTP Scheme (e.g. http://)
             * will not be opened successfully anyway
             */
            if( array_key_exists( 'path', $data ) ) {

                $this -> basepath = ltrim( $data['path'], '/' );
            }

        } else {

            preg_match( self::PROTOCOL_REGEXP, $_SERVER['SERVER_PROTOCOL'], $match );

            if( array_key_exists( 'protocol', $match ) ) {
                $this -> protocol -> name = strtolower( $match['protocol'] );
            }

            if( array_key_exists( 'version', $match ) ) {
                $this -> protocol -> version = $match['version'];
            }

            // URI Basepath

            /**
             * @internal
             *
             * By default, if a basepath has not been provided as a
             * Parameter Option yet, its value is whatever lies before
             * the first occurrence of the DIRECTORY_SEPARATOR constant
             * in SCRIPT_FILENAME Server Variable after being stripped
             * of DOCUMENT_ROOT Server Variable. E.g:
             *
             * ````
             * Document Root: D:\root
             * Project Root Folder: /project
             * Script Filename: D:\root/project/index.php
             * Basepath: project
             * ````
             */
            if( empty( $this -> options -> basepath ) ) {

                $path = trim( strtr( $_SERVER['SCRIPT_FILENAME'], [ $_SERVER['DOCUMENT_ROOT'] => '' ] ), DIRECTORY_SEPARATOR );

                if( ( $slash = strpos( $path, DIRECTORY_SEPARATOR ) ) !== FALSE ) {
                    $this -> basepath = substr( $path, 0, $slash );
                }
            }

            // Adding default Request Headers

            if( defined( 'TURBO_MODE' ) && TURBO_MODE === FALSE ) {

                if( ( $headers = apache_response_headers() ) ) {

                    try {

                        $this -> headers -> addHeader( $headers );

                    } catch( InvalidArgumentException $e ) {

                        /**
                         * @internal
                         * We're silencing the InvalidArgumentException in
                         * order to not break the Response Flow
                         *
                         * However, if this Exception is caught, no
                         * Response Headers will be available
                         */
                    }
                }
            }
        }

        // Post-initialization Configuration

        $this -> configure();
    }

    /**
     * Get Host
     *
     * @return string
     *  Request Host
     */
    public function getHost() : string {
        return $this -> host;
    }

    /**
     * Set BasePath
     *
     * @param string $basepath
     *  Request Base Path
     *
     * @return \Next\HTTP\Request
     *  Request Object (Fluent Interface)
     */
    public function setBasepath( $basepath ) : Request {

        $basepath = new Path( [ 'value' => $basepath ] );

        $this -> basepath = $basepath -> clean() -> get();

        return $this;
    }

    /**
     * Get Basepath
     *
     * @return string
     *  The BasePath
     */
    public function getBasepath() : string {
        return $this -> basepath;
    }

    /**
     * Get Request Uri
     *
     * @param boolean $stripBasePath
     *  If TRUE removes what is defined in Request:basepath property
     *
     * @return string
     *  The Request URI
     */
    public function getRequestURI( $stripBasePath = TRUE ) : string {

        $scheme = parse_url( $this -> uri, PHP_URL_SCHEME );

        // Should we return the Requested URI (almost) untouched?

        if( ! $stripBasePath || $scheme !== NULL ) {
            return trim( $this -> uri, '/' );
        }

        $uri = preg_replace(
            sprintf( '#%s#i', $this -> basepath ), '', $this -> uri, 1
        );

        $uri = trim( $uri, '/' );

        return strtr( ( empty( $uri ) ? '/' : $uri ), [ '//' => '/' ] );
    }

    /**
     * Get Full URL
     *
     * @param boolean|optional $includeSchema
     *  Defines whether or not the HTTP Schema, if available, will be included
     *  or not in the final string. Defaults to TRUE
     *
     * @return string
     *  The full URL
     */
    public function getURL( $includeSchema = TRUE ) : string {

        $scheme = parse_url( $this -> uri, PHP_URL_SCHEME );

        if( $scheme === NULL ) {

            $rUri = $this -> getRequestURI();

            $url = sprintf(
                '%s/%s', $this -> getBaseURL(), ( $rUri != '/' ? $rUri : NULL )
            );

            return ( $includeSchema !== FALSE ? $url : preg_replace( '#^https?:\/\/#', '', $url ) );
        }

        return preg_replace( '#^https?:\/\/#', '', $this -> uri );
    }

    /**
     * Get Base URL
     *
     * Base URL is the union of: Scheme (HTTP, HTTPS, FTP...), HTTP Host and Base Path
     *
     * @return string
     *  The Base URL
     */
    public function getBaseURL() : string {

        $parts = parse_url( $this -> uri );

        if( isset( $parts['scheme'] ) ) {
            return sprintf( '%s://%s', $parts['scheme'], $parts['host'] );
        }

        return rtrim(

            sprintf(

                '%s://%s/%s',

                strtolower( self::SCHEME_HTTP ), $_SERVER['HTTP_HOST'],

                $this -> basepath
            ), '/'
        );
    }

    /**
     * Get Request Referrer
     *
     * @return string
     *  The Request Referrer coming from $_SERVER
     */
    public function getReferrer() : string {
        return $_SERVER['HTTP_REFERER'];
    }

    /**
     * Get Request Protocol
     *
     * @param boolean|optional $includeVersion
     *  If set as TRUE and Protocol Version hasn't been detected
     *  during Additional Initialization stage then
     *  `\Next\HTTP\Request::getProtocolVersion()`
     *  will be invoked in order to *try to* provide Server Protocol
     *  version too
     *
     *  This may be a little slow! u.u'
     *
     * @return stdClass
     *  Server Protocol Informations
     */
    public function getProtocol( $includeVersion = FALSE ) : \stdClass {

        if( ! isset( $this -> protocol -> version ) && $includeVersion !== FALSE ) {
            $this -> getProtocolVersion();
        }

        return $this -> protocol;
    }

    // Accessory Methods

    /**
     * Get Request Method
     *
     * Just a formality, since its use is not very necessary
     *
     * @return string
     *  The Request Method
     */
    public function getRequestMethod() : string {
        return $this -> method;
    }

    /**
     * Is this a GET method request?
     *
     * @return boolean
     *  TRUE if current Request is a GET Request and FALSE otherwise
     */
    public function isGet() : bool {
        return ( $this -> method === self::GET );
    }

    /**
     * Is this a PATCH method request?
     *
     * @return boolean
     *  TRUE if current Request is a PATCH Request and FALSE otherwise
     */
    public function isPatch() : bool {
        return ( $this -> method === self::PATCH );
    }

    /**
     * Is this a POST method request?
     *
     * @return boolean
     *  TRUE if current Request is a POST Request and FALSE otherwise
     */
    public function isPost() : bool {
        return ( $this -> method === self::POST );
    }

    /**
     * Is this a PUT method request?
     *
     * @return boolean
     *  TRUE if current Request is a PUT Request and FALSE otherwise
     */
    public function isPut() : bool {
        return ( $this -> method === self::PUT );
    }

    /**
     * Is this a DELETE method request?
     *
     * @return boolean
     *  TRUE if current Request is a DELETE Request and FALSE otherwise
     */
    public function isDelete() : bool {
        return ( $this -> method === self::DELETE );
    }

    /**
     * Is this a HEAD method request?
     *
     * @return boolean
     *  TRUE if current Request is a HEAD Request and FALSE otherwise
     */
    public function isHead() : bool {
        return ( $this -> method === self::HEAD );
    }

    /**
     * Is this an OPTIONS method request?
     *
     * @return boolean
     *  TRUE if current Request is an OPTIONS Request and FALSE otherwise
     */
    public function isOptions() : bool {
        return ( $this -> method === self::OPTIONS );
    }

    /**
     * Is this a CONNECT method request?
     *
     * @return boolean
     *  TRUE if current Request is a CONNECT Request and FALSE otherwise
     */
    public function isConnect() : bool {
        return ( $this -> method === self::CONNECT );
    }

    /**
     * Is this a TRACE method request?
     *
     * @return boolean
     *  TRUE if current Request is a TRACE Request and FALSE otherwise
     */
    public function isTrace() : bool {
        return ( $this -> method === self::TRACE );
    }

    /**
     * Check if we're dealing with an AJAX Request
     *
     * @return boolean
     *  TRUE if is an AJAX Request and FALSE otherwise
     */
    public function isAjax() : bool {

        $header = $this -> headers -> item( 'X-Requested-With' );

        if( $header === -1 ) return FALSE;

        return ( $header instanceof Field &&
                    strcasecmp( $header -> getValue(), 'XMLHttpRequest' ) == 0 );
    }

    /**
     * Check if we're dealing with a Flash Request
     *
     * @return boolean
     *  TRUE if is a Flash Request and FALSE otherwise
     */
    public function isFlash() : bool {

        $header = $this -> headers -> item( 'User-Agent' );

        if( $header === -1 ) return FALSE;

        return ( $header instanceof Field &&
                    strpos( $header -> getValue(), 'flash' ) !== FALSE );
    }

    /**
     * Check if we're under a Secure (HTTPS) connection
     *
     * @return boolean
     *  TRUE if is under an SSL Request and FALSE otherwise
     */
    public function isSsl() : bool {
        return $this -> getData( $_SERVER, 'HTTPS' ) === 'on';
    }

    /**
     * Check if we're runing under Command Line (CLI)
     *
     * @return boolean
     *  TRUE if is under Command Line (CLI) and FALSE otherwise
     */
    public function isCli() : bool {
        return ( PHP_SAPI == 'cli' || PHP_SAPI == 'cli-server' );
    }

    /**
     * Get Connection Adapter, available only in External Requests
     *
     * @return \Next\HTTP\Stream\Adapter\Adapter|NULL
     *  On external Requests, an Object instance of
     *  `\Next\HTTP\Stream\Adapter\Adapter` will be returned.
     *  On internal (i.e routed) Requests, nothing is returned
     */
    public function getAdapter() :? Adapter {
        return $this -> adapter;
    }

    // Data Manipulation-related Methods

    /**
     * Set Query Data a.k.a. GET Data
     *
     * @param array $data
     *  Params to be used as Dynamic (a.k.a. GET) Params
     *
     * @return \Next\HTTP\Request
     *  Request Instance (Fluent Interface)
     */
    public function setQuery( array $data ) : Request {

        $this -> queryData = array_merge( $this -> queryData, $data );

        return $this;
    }

    /**
     * Get Query Data (a.k.a. GET Data)
     *
     * @param string|optional $key
     *  Desired Dynamic Param
     *
     * @return mixed
     *  If **$key** isn't set or it's `NULL`, all GET Data (i.e. $_GET)
     *  will be returned as array
     *
     *  If defined key exists as GET Data it'll be returned
     *  Otherwise, it'll return `NULL` instead
     *
     *  If Request Object has been configured to automatically filter data -AND-
     *  the indicator 'G' is present in 'filterable' Parameter Option it'll
     *  be done
     *
     * @see Request::getData()
     * @see Request::sanitize()
     */
    public function getQuery( $key = NULL ) {

        return $this -> getData( $this -> queryData, $key,
                    ( strpos( $this -> options -> filterable, 'G' ) !== FALSE ) );
    }

    /**
     * Set POST Data
     *
     * @param array|string $field
     *  POST Field
     *
     * @param mixed|optional $value
     *  Field Value
     *
     * @return \Next\HTTP\Request
     *  Request Instance (Fluent Interface)
     *
     * @throws \Next\Exception\Exceptions\BadMethodCallException
     *   <strong>$value</strong> argument is NULL, case in which a
     *  possible RAW Data should be considered instead
     */
    public function setPostData( $field, $value = NULL ) : Request {

        if( (array) $field === $field ) {

            foreach( $field as $f => $v ) {

                $this -> setPostData( $f, $v );
            }

        } else {

            if( $value === NULL ) {

                throw new BadMethodCallException(

                    'Wrong use of Request::setPostData(). POST Data must have a field name and a value

                    If you don\'t have a field name, consider use Request::setRawPostData() instead'
                );
            }

            $this -> postData[ $field ] = $value;
        }

        return $this;
    }

    /**
     * Set RAW POST Data
     *
     * @note
     *  Raw Data will NOT be URLEncoded
     *
     * @param array|string $field
     *  Raw POST Field
     *
     * @param mixed|optional $value
     *  Field Value
     *
     * @return \Next\HTTP\Request
     *  Request Instance (Fluent Interface)
     */
    public function setRawPostData( $field, $value = NULL ) : Request {

        if( (array) $field === $field ) {

            foreach( $field as $f => $v ) {

                $this -> setRawPostData( $f, $v );
            }

        } else {

            if( $value !== NULL ) {

                $this -> rawPostData[ $field ] = $value;

            } else {

                $this -> rawPostData[] = $field;
            }
        }

        return $this;
    }

    /**
     * Get POST Data
     *
     * @param string|optional $key
     *  Desired POST Param
     *
     * @return mixed
     *  If **$key** isn't set or it's `NULL`, all POST Data (i.e. $_POST)
     *  will be returned as array
     *
     *  If defined key exists as POST Data it'll be returned
     *  Otherwise, it'll return `NULL` instead
     *
     *  If Request Object has been configured to automatically filter data -AND-
     *  the indicator 'P' is present in 'filterable' Parameter Option it'll
     *  be done
     *
     * @throws \Next\Exception\Exceptions\BadMethodCallException
     *  Throw if trying to retried Post Data while not being in a POST Request
     *
     * @see Request::getData()
     * @see Request::sanitize()
     */
    public function getPost( $key = NULL ) {

        if( ! $this -> isPost() ) {

            throw new BadMethodCallException(
                'POST Data is only available on POST Requests'
            );
        }

        return $this -> getData( $this -> postData, $key,
                    ( strpos( $this -> options -> filterable, 'P' ) !== FALSE ) );
    }

    /**
     * Get Raw Data
     *
     * @return string|NULL
     *  If there is RAW Data to return, it will be
     *  If not or if RAW Data is made of blanks, NULL is returned
     */
    public function getRawData() :? string {

        $data = file_get_contents( 'php://input' );

        return ( strlen( trim( $data ) ) > 0 ? $data : NULL );
    }

    /**
     * Get SERVER Data
     *
     * @param string|optional $key
     *  Desired SERVER Param
     *
     * @return mixed
     *  If **$key** isn't set or it's `NULL`, all Server Data (i.e. $_SERVER)
     *  will be returned as array
     *
     *  If defined key exists as Server Data it'll be returned
     *  Otherwise, it'll return `NULL` instead
     *
     *  If Request Object has been configured to automatically filter data -AND-
     *  the indicator 'S' is present in 'filterable' Parameter Option it'll
     *  be done
     *
     * @see Request::getData()
     * @see Request::sanitize()
     */
    public function getServer( $key = NULL ) {

        return $this -> getData( $_SERVER, $key,
                    ( strpos( $this -> options -> filterable, 'S' ) !== FALSE ) );
    }

    /**
     * Get Environment Data
     *
     * @param string|optional $key
     *  Desired environment variable
     *
     * @param boolean|option $strict
     *  Condition whether or not the search will be case-sensitive.
     *  Defaults to FALSE because $_ENV may be empty if, for some reason, it
     *  has been explicitly disabled in PHP.INI 'variables_order directive'
     *
     * @return mixed
     *  If **$key** isn't set or it's `NULL`, all Environment Data (i.e. $_ENV)
     *  will be returned as array
     *
     *  If defined key exists as Environment Data it'll be returned
     *  Otherwise, it'll return `NULL` instead
     *
     *  If Request Object has been configured to automatically filter data -AND-
     *  the indicator 'G' is present in 'filterable' Parameter Option it'll
     *  be done
     *
     * @see Request::getData()
     * @see Request::sanitize()
     */
    public function getEnv( $key = NULL, $strict = FALSE ) {

        if( function_exists( 'ini_get' ) &&
                strpos( 'E', ini_get( 'variables_order' ) !== FALSE ) &&
                    $strict !== FALSE ) {

            $source = $_ENV;

        } else {
            $source = getenv( $key );
        }

        return $this -> getData( $source, $key,
                    ( strpos( $this -> options -> filterable, 'E' ) !== FALSE ) );
    }

    /**
     * Wrapper Method for Data Retrieving
     *
     * @param array $source
     *  Data-source
     *
     * @param string|optional $key
     *  Desired Param
     *
     * @param boolean|optional $sanitize
     *  Flag to condition whether or not found data, if any, should be sanitized
     *
     * @return mixed
     *  If **$key** isn't set or it's `NULL`, all the source data will be
     *  returned as an array
     *
     *  If defined key exists ins source data it'll be returned.
     *  Otherwise, it'll return `NULL` instead
     *
     *  If Request Object has been configured to automatically filter data
     *  it'll be done
     *
     * @see Request::sanitize()
     */
    public function getData( array $source, $key = NULL, $sanitize = TRUE ) {

        if( $key === NULL ) {
            $data = $source;
        } else {
            $data = array_key_exists( $key, $source ) ? $source[ $key ] : NULL;
        }

        return ( $sanitize !== FALSE && $data !== NULL ? $this -> sanitize( $data ) : $data );
    }

    // Execution Flow-related Methods

    /**
     * Send the Request
     *
     * @return \Next\HTTP\Response
     *  HTTP Response Object
     */
    public function send() : Response {

        // Setting Up a Fallback HTTP Stream Adapter

        if( $this -> adapter === NULL ) {

            $this -> adapter = new Socket(

                $this -> getURL(), 'r',

                new SocketContext(

                    [ 'http' => [ 'method' => $this -> method ] ]
                )
            );
        }

        // Shortening HTTP Stream Context

        $context = $this -> adapter -> getContext();

        // Request Method

        $context -> setOptions(
            [ 'http' => [ 'method' => $this -> method ] ]
        );

        //---------------

        // Request Method-related tasks

        switch( $this -> method ) {

            case self::GET:
                // Nothing so far...
            break;

            case self::POST;
                $this -> sendPost();
            break;

            case self::PUT:
                // Nothing so far...
            break;

            case self::DELETE:
                // Nothing so far...
            break;
        }

        /**
         * @internal
         *
         * Cookies are defined in a Header Field too, so they come first
         */
        $this -> headers -> addHeader(
            $this -> cookies -> getCookies( TRUE )
        );

        // Headers

        $context -> setOptions(

            [ 'http' => [
                'header' => $this -> headers -> getHeaders( TRUE ) ]
            ]
        );

        // Building Response

        try {

           $reader = new Reader( $this -> adapter );

           return new Response(

               $reader -> readAll(),

               $this -> adapter -> getMetaData()
           );

        } catch( LengthException | RuntimeException $e ) {

            return new Response;
        }
    }

    // Parameterizable Interface Method Overwriting

    /**
     * Set class options
     *
     * @return array
     *  HTTP Request Object Class Default Options
     */
    public function setOptions() : array {

        return [

            // Request-related Parameter Options

            /**
             * An HTTP Stream Adapter that'll be used for external Requests
             */
            'adapter'  => [ 'type' => 'Next\HTTP\Stream\Adapter\Adapter', 'required' => FALSE ],

            /**
             * The Request URI.
             * Defaults to the REQUEST_URI entry on $_SERVER but on
             * External Requests the Filename/URL will be used instead
             */
            'uri'      => [ 'required' => FALSE, 'default' => $_SERVER['REQUEST_URI'] ],

            /**
             * The Host being requested.
             * Defaults to the HTTP_HOST entry on $_SERVER
             */
            'host'     => [ 'required' => FALSE, 'default' => $_SERVER['HTTP_HOST'] ],

            /**
             * The Request Method.
             * Defaults to the REQUEST_METHOD entry on $_SERVER
             */
            'method'   => [ 'required' => FALSE, 'default' => $_SERVER['REQUEST_METHOD'] ],

            /**
             * Basepath is a substring present between HTTP Schema, domain and,
             * sometimes, connection port and the Request URI
             * Defaults to an empty string, meaning there's no default basepath
             */
            'basepath' => '',

            // Configuration-related Parameter Options

            /**
             * Defines whether or not Request Data will be automatically filtered
             * or not.
             * Defaults to TRUE, enforcing security
             */
            'autoFilter' => [ 'required' => FALSE, 'default' => TRUE ],

            /**
             * Defines which data are filterable.
             * Defaults to "GP", like PHP.INI 'variables_order' directive,
             * meaning that all (G)ET and (P)OST Data will receive default
             * Filters (see below)
             */
            'filterable' => [ 'required' => FALSE, 'default' => 'GP' ],

            /**
             * A Next\Filter\Sanitizer Object with some default filters to be
             * applied to Requested Data.
             *
             * By default the following Filters will be applying, in this order:
             *
             * - Next\Filter\StripTags
             * - Next\Filter\Slashify
             * - Next\Filter\HTMLEntities
             * - Next\Filter\Whitespace
             *
             * All of them with their Default Parameter Options, except
             * for Next\Filter\StripTags which has been configure to not allow
             * any tag
             *
             * But if comes the need to reconfigure them it's also possible. E.g:
             *
             * From a Page Controller context, one just need to access its
             * Request Object (`$this -> request`), find the Filter that'll
             * be modified among the Filters defined and merge the
             * new Parameter Options:
             *
             * ````
             * if( ( $filter = $this -> request -> options -> filters -> item( 'StripTags' ) ) !== -1 ) {
             *     $filter -> getOptions() -> merge( [ 'allowedTags' => [ 'strong' ] ] );
             * }
             * ````
             *
             * Although we do know the StripTags Filter is the first one we
             * could access it manually by passing '0' (zero) to
             * `Next\Components\Collections\Lists::item()` from which
             * The Sanitizer derives BUT this is not 100% reliable as,
             * in the future, the Filters *may* exchange positions or be removed.
             *
             * That's we search by name and, of course, we test if the Filter
             * has been found to not receive errors because we could be
             * calling `getOptions()` on `NULL`
             */
            'filters' => [

                'required' => FALSE,

                'default' => [
                    [ 'filter' => 'Next\Filter\StripTags', 'args' => [ 'allowedTags' => [] ] ],
                    [ 'filter' => 'Next\Filter\Slashify', 'args' => [] ],
                    [ 'filter' => 'Next\Filter\HTMLEntities', 'args' => [] ],
                    [ 'filter' => 'Next\Filter\Whitespace', 'args' => [] ],
                ]
            ]
        ];
    }

    // Configurable Interface Method Implementation

    /**
     * Post-Initialization Configuration.
     * Builds a Next\Filter\Sanitizer Object with all Filters defined
     */
    public function configure() {

        if( ! $this -> options -> autoFilter ) return;

        // Building Sanitizer Collection

        $sanitizer = new \Next\Filter\Sanitizer;

        foreach( $this -> options -> filters as $filters ) {
            $sanitizer -> add( new $filters['filter']( $filters['args'] ) );
        }

        $this -> options -> filters = $sanitizer;
    }

    // Auxiliary Methods

    /**
     * Get Request Protocol Version
     *
     * @internal
     *
     * In case the Request Protocol can't be detected during the
     * Additional Initialization stage -AND- while requesting its
     * data through `Request::getProtocol()` the full spec is
     * requested (i.e. setting method's argument to TRUE) we'll try
     * to detect it AGAIN here.
     *
     * This delay is only because get_headers() function does another
     * Request to target Server and Request Protocol isn't an extremely
     * useful information nowadays
     *
     * @return integer|NULL
     *  Protocol version if able to match and NULL otherwise
     */
    private function getProtocolVersion() : void {

        $headers = get_headers( $this -> uri );

        if( $headers !== FALSE ) {

            $data = array_shift( $headers );

            preg_match( self::PROTOCOL_REGEXP, $data, $match );

            if( array_key_exists( 'version', $match ) ) {
                $this -> protocol -> version = $match['version'];
            }
        }
    }

    // Request Method-related Wrappers

    /**
     * Wrapper method for POST Request routines
     */
    private function sendPost() : void {

        /**
         * @internal
         * If we have Raw Data to send, "normal" POST Data
         * will be send as Raw too, because they are considered the same
         */
        if( count( $this -> rawPostData ) != 0 ) {

            $POSTDATA = implode( '&', array_merge( $this -> postData, $this -> rawPostData ) );

        } else {

            $POSTDATA = http_build_query( $this -> postData, '', '&' );
        }

        // If we have any POST Daata to send...

        if( ! empty( $POSTDATA ) ) {

            $this -> adapter -> getContext() -> setOptions(
                [ 'http' => [ 'content' => $POSTDATA ] ]
            );

            /**
             * @internal
             * ... we have to add the proper Content-type Header Field,
             * if missing, in order to be sure a PHP Error will not be raised
             */
            if( $this -> headers -> find( 'Content-Type' ) === -1 ) {

                try {

                    $this-> headers -> addHeader(
                        new ContentType( [ 'value' => 'application/x-www-form-urlencoded' ] )
                    );

                } catch( Exception $e ) {

                    // Silenced because we're 100% sure this will never be caught :P
                }
            }
        }
    }

    /**
     * Wrapper method to apply default Filters to input data
     *
     * @param  mixed| $data
     *  Data to filter.
     *  If an array is provided, all entries will fields will be
     *  filtered recursively
     *
     * @return mixed
     *  Input data filtered
     */
    private function sanitize( $data ) {

        if( count( $this -> options -> filters ) == 0 ) return $data;

        if( is_array( $data ) ) {

            foreach( $data as $field => $value ) {
                $data[ $field ] = $this -> sanitize( $value );
            }
        }

        if( is_scalar( $data ) ) {

            // Injecting data to be filtered

            $this -> options -> filters -> setData( $data );

            $data = $this -> options -> filters -> filter();
        }

        return $data;
    }
}