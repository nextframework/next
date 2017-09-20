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

use Next\HTTP\Request\RequestException;             # Request Exception Classes
use Next\HTTP\Stream\Adapter\AdapterException;      # Adapter Exception Class
use Next\HTTP\Headers\Fields\FieldsException;       # Headers Fields Exception Class

use Next\HTTP\Headers\Fields\Field;                 # Header Fields Interface
use Next\HTTP\Stream\Adapter\Adapter;               # HTTP Stream Adapter Interface

use Next\Components\Object;                         # Object Class
use Next\FileSystem\Path;                           # FileSystem Path Data-type Class
use Next\Components\Parameter;                      # Parameter Object
use Next\Components\Invoker;                        # Invoker Class
use Next\HTTP\Stream\Adapter\Socket;                # HTTP Stream Socket Adapter Class
use Next\HTTP\Stream\Context\SocketContext;         # HTTP Stream Socket Context Class
use Next\HTTP\Headers\Fields\Entity\ContentType;    # Content-Type Header Class
use Next\HTTP\Stream\Reader;                        # HTTP Stream Reader
use Next\File\Tools;                                # File Tools Class

/**
 * Request Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Request extends Object {

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
     * @var \Next\HTTP\Headers\RequestHeaders $headers
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
     * @var string $protocol
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
    public function init() {

        /**
         * HTTP Request Class default Options
         *
         * @internal
         *
         * Because, obviously, variables can't be used outside class
         * methods, differently than all other classes adept of the
         * Parameterizable Concept, HTTP Request Class' Parameter Object
         * is manually built during the Additional Initialization stage
         *
         * It's a small price to pay instead of defining these default
         * variables every time, everywhere the Request Object is needed
         *
         * \Next\Components\Object::setOptions() is being used, but
         * without a particular reason, after all the Request
         * shouldn't derive any other
         *
         * And, the great trick (^_^). The third "argument'
         * for \Next\Components\Parameter, holding the options entered
         * when instantiating the Object comes through Object::$options
         * itself because one way or another its procedures were done
         */
        $this -> options = new Parameter(

            [
                'uri'      => $_SERVER['REQUEST_URI'],
                'host'     => $_SERVER['HTTP_HOST'],
                'method'   => $_SERVER['REQUEST_METHOD'],
                'basepath' => ''
            ],

            NULL, $this -> options
        );

        // Cookies Management Object

        $this -> cookies = new Cookies;

        // Request Headers Management Object

        $this -> headers = new Headers\RequestHeaders;

        // Extend Object Context to Headers', Cookies and Browser Classes

        $this -> extend( new Invoker( $this, $this -> headers ) )
              -> extend( new Invoker( $this, $this -> cookies ) )
              -> extend( new Invoker( $this, new Request\Browser ) );

        // Initializing Data Properties with superglobals

        $this -> queryData   = $_GET;

        $this -> postData    = $_POST;

        //---------------------------

        $this -> checkIntegrity();

        // Setting Up Basic Informations

            // Do we have an HTTP Stream Adapter?

        if( isset( $this -> options -> adapter ) ) {

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

            // Protocol... "name"

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

                $path = trim( str_replace( $_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME'] ), DIRECTORY_SEPARATOR );

                if( ( $slash = strpos( $path, DIRECTORY_SEPARATOR ) ) !== FALSE ) {
                    $this -> basepath = substr( $path, 0, $slash );
                }
            }

            // Adding default Request Headers

            if( defined( 'TURBO_MODE' ) && ! TURBO_MODE === TRUE ) {
                $this -> addDefaultHeaders();
            }
        }
    }

    /**
     * Get Host
     *
     * @return string
     *  Request Host
     */
    public function getHost() {
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
    public function setBasepath( $basepath ) {

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
    public function getBasepath() {
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
    public function getRequestURI( $stripBasePath = TRUE ) {

        $scheme = parse_url( $this -> uri, PHP_URL_SCHEME );

        // Should we return the Requested URI (almost) untouched?

        if( ! $stripBasePath || ! is_null( $scheme ) ) {
            return trim( $this -> uri, '/' );
        }

        //$uri = trim( str_ireplace( $this -> basepath, '', $this -> uri ), '/' );
        $uri = preg_replace(

            sprintf( '#%s#i', $this -> basepath ), '', $this -> uri, 1
        );

        $uri = trim( $uri, '/' );

        return str_replace( '//', '/', ( empty( $uri ) ? '/' : $uri ) );
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
    public function getURL( $includeSchema = TRUE ) {

        $scheme = parse_url( $this -> uri, PHP_URL_SCHEME );

        if( is_null( $scheme ) ) {

            $rUri = $this -> getRequestURI();

            $url = sprintf(
                '%s/%s', $this -> getBaseUrl(), ( $rUri != '/' ? $rUri : NULL )
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
    public function getBaseUrl() {

        $parts = parse_url( $this -> uri );

        if( isset( $parts['scheme'] ) ) {

            return sprintf( '%s://%s', $parts['scheme'], $parts['host'] );

        } else {

            return sprintf(

                '%s://%s/%s',

                strtolower( self::SCHEME_HTTP ), $_SERVER['HTTP_HOST'],

                $this -> basepath
            );
        }
    }

    /**
     * Get Request Referrer
     *
     * @return string
     *  The Request Referrer coming from $_SERVER
     */
    public function getReferrer() {
        return $_SERVER['HTTP_REFERER'];
    }

    /**
     * Get Request Protocol
     *
     * @param boolean|optional $includeVersion
     *  If TRUE \Next\HTTP\Request::getProtocolVersion() will be
     *  invoked in order to *try to) provide Server Protocol version too
     *
     *  This can be a little slow!
     *
     * @return string|stdClass
     *  If <strong>$includeVersion</strong> is set to TRUE, all informations
     *  about Server protocol will be returned as an
     *   {@link http://php.net/manual/en/reserved.classes.php stdClass Object}
     *
     *  Otherwise, only Server Protocol... "name" will, as string
     */
    public function getProtocol( $includeVersion = FALSE ) {

        if( ! isset( $this -> protocol -> version ) && $includeVersion !== FALSE ) {
            $this -> getProtocolVersion();
        }

        return $this -> protocol;
    }

    /**
     * Get Request Method
     *
     * Just a formality, since its use is not very necessary
     *
     * @return string
     *  The Request Method
     */
    public function getRequestMethod() {
        return $this -> method;
    }

    /**
     * Is this a GET method request?
     *
     * @return bool
     *  TRUE if current Request is a GET Request and FALSE otherwise
     */
    public function isGet() {
        return ( $this -> method === self::GET );
    }

    /**
     * Is this a PATCH method request?
     *
     * @return bool
     *  TRUE if current Request is a PATCH Request and FALSE otherwise
     */
    public function isPatch() {
        return ( $this -> method === self::PATCH );
    }

    /**
     * Is this a POST method request?
     *
     * @return bool
     *  TRUE if current Request is a POST Request and FALSE otherwise
     */
    public function isPost() {
        return ( $this -> method === self::POST );
    }

    /**
     * Is this a PUT method request?
     *
     * @return bool
     *  TRUE if current Request is a PUT Request and FALSE otherwise
     */
    public function isPut() {
        return ( $this -> method === self::PUT );
    }

    /**
     * Is this a DELETE method request?
     *
     * @return bool
     *  TRUE if current Request is a DELETE Request and FALSE otherwise
     */
    public function isDelete() {
        return ( $this -> method === self::DELETE );
    }

    /**
     * Is this a HEAD method request?
     *
     * @return bool
     *  TRUE if current Request is a HEAD Request and FALSE otherwise
     */
    public function isHead() {
        return ( $this -> method === self::HEAD );
    }

    /**
     * Is this an OPTIONS method request?
     *
     * @return bool
     *  TRUE if current Request is an OPTIONS Request and FALSE otherwise
     */
    public function isOptions() {
        return ( $this -> method === self::OPTIONS );
    }

    /**
     * Is this a CONNECT method request?
     *
     * @return bool
     *  TRUE if current Request is a CONNECT Request and FALSE otherwise
     */
    public function isConnect() {
        return ( $this -> method === self::CONNECT );
    }

    /**
     * Is this a TRACE method request?
     *
     * @return bool
     *  TRUE if current Request is a TRACE Request and FALSE otherwise
     */
    public function isTrace() {
        return ( $this -> method === self::TRACE );
    }

    /**
     * Check if we're dealing with an AJAX Request
     *
     * @return boolean
     *  TRUE if is an AJAX Request and FALSE otherwise
     */
    public function isAjax() {

        $header = $this -> headers -> findHeader( 'X-Requested-With' );

        return ( $header instanceof Field && $header -> getValue() == 'XMLHttpRequest' );
    }

    /**
     * Check if we're dealing with a Flash Request
     *
     * @return boolean
     *  TRUE if is a Flash Request and FALSE otherwise
     */
    public function isFlash() {

        $header = $this -> headers -> findHeader( 'User-Agent' );

        return ( $header instanceof Field &&
                 strpos( $header -> getValue(), 'flash' ) !== FALSE );
    }

    /**
     * Check if we're under a Secure (HTTPS) connection
     *
     * @return boolean
     *  TRUE if is under an SSL Request and FALSE otherwise
     */
    public function isSsl() {
        return $this -> getData( $_SERVER, 'HTTPS' ) === 'on';
    }

    /**
     * Check if we're runing under Command Line (CLI)
     *
     * @return boolean
     *  TRUE if is under Command Line (CLI) and FALSE otherwise
     */
    public function isCli() {
        return ( PHP_SAPI == 'cli' || PHP_SAPI == 'cli-server' );
    }

    /**
     * Set Query Data a.k.a. GET Data
     *
     * @param array $data
     *  Params to be used as Dynamic (a.k.a. GET) Params
     *
     * @return \Next\HTTP\Request
     *  Request Instance (Fluent Interface)
     */
    public function setQuery( array $data ) {

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
     *
     *   <p>
     *       If <strong>$key</strong> is equal to NULL, all the
     *       Query Data will be returned
     *   </p>
     *
     *   <p>
     *       If <strong>$key</strong> is NOT NULL AND given key exists in Query Data array will
     *       be returned
     *   </p>
     *
     *   <p>Otherwise, returns NULL</p>
     */
    public function getQuery( $key = NULL ) {
        return $this -> getData( $this -> queryData, $key );
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
     * @throws \Next\HTTP\Request\RequestException
     *   <strong>$value</strong> argument is NULL, case in which a
     *  possible RAW Data should be considered instead
     */
    public function setPostData( $field, $value = NULL ) {

        if( is_array( $field ) ) {

            foreach( $field as $f => $v ) {

                $this -> setPostData( $f, $v );
            }

        } else {

            if( is_null( $value ) ) {

                throw RequestException::wrongUse(

                    'Wrong use of Request::setPostData(). POST Data must have a field name and a value.

                    <br />

                    If you don\'t have a field name, consider use Request::setRawPostData() instead'
                );
            }

               $this -> postData[ $field ] =& $value;
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
    public function setRawPostData( $field, $value = NULL ) {

        if( is_array( $field ) ) {

            foreach( $field as $f => $v ) {

                $this -> setRawPostData( $f, $v );
            }

        } else {

            if( ! is_null( $value ) ) {

                $this -> rawPostData[ $field ] =& $value;

            } else {

                $this -> rawPostData[] =& $field;
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
     *
     *   <p>
     *       If <strong>$key</strong> is equal to NULL, all the
     *       POST Data will be returned
     *   </p>
     *
     *   <p>
     *      If <strong>$key</strong> is NOT NULL AND given key exists
     *      in POST Data array will be returned
     *   </p>
     *
     *   <p>Otherwise, returns FALSE</p>
     */
    public function getPost( $key = NULL ) {
        return $this -> getData( $this -> postData, $key );
    }

    /**
     * Get Raw Data
     *
     * @return string|NULL
     *
     *   <p>If there is RAW Data to return, it will be.</p>
     *
     *   <p>Otherwise, or if RAW Data is made by blanks, NULL is returned</p>
     */
    public function getRawData() {

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
     *
     *   <p>
     *       If <strong>$key</strong> is equal to NULL, all the
     *       SERVER Data will be returned
     *   </p>
     *
     *   <p>
     *       If <strong>$key</strong> is NOT NULL AND given key exists
     *       in SERVER Data array will be returned
     *   </p>
     *
     *   <p>Otherwise, returns FALSE</p>
     */
    public function getServer( $key = NULL ) {
        return $this -> getData( $_SERVER, $key );
    }

    /**
     * Get Environment Data
     *
     * @param string|optional $key
     *  Desired environment variable
     *
     * @return mixed
     *
     *  <p>
     *      If directive <a href="http://us.php.net/manual/en/ini.core.php#ini.variables-order">variables_order</a>
     *      has the "E" indicator, the data will be retrieved from $_ENV superglobal.
     *  </p>
     *
     *  <p>
     *      In this case, if <strong>$key</strong> is not specified, all Environment
     *      Variables available will be returned
     *  </p>
     *
     *  <p>
     *     If <strong>$key</strong> is defined and it exists among Environment Variables,
     *     if any, it will be returned. Otherwise, NULL is returned.
     *  </p>
     *
     *  <p>
     *      But, if this directive doesn't have the "E" indicator -OR- if for some
     *      reason, the ini_get() function doesn't exist (likely because it has
     *      been disabled for pseudo-security reasons) the getenv() function will
     *      be used instead, and thus, if the variable doesn't exist, FALSE is returned
     *  </p>
     */
    public function getEnv( $key = NULL ) {

        if( function_exists( 'ini_get' ) ) {

            if( strpos( 'E', ini_get( 'variables_order' ) !== FALSE ) ) {
                return $this -> getData( $_ENV, $key );
            }
        }

        return getenv( $key );
    }

    /**
     * Wrapper Method for Data Retrieve
     *
     * @param array $source
     *  Data-source, super-global or class property
     *
     * @param string|optional $key
     *  Desired Param
     *
     * @return mixed
     *
     *   <p>
     *       If <strong>$key</strong> is equal to NULL, all Data-source
     *       will be returned
     *   </p>
     *
     *   <p>
     *       If </strong>$key is NOT NULL AND given key exists in
     *       Source Data array it will be returned
     *   </p>
     *
     *   <p>Otherwise, returns NULL</p>
     */
    public function getData( array $source, $key = NULL ) {

        if( is_null( $key ) ) return $source;

        return array_key_exists( $key, $source ) ? $source[ $key ] : NULL;
    }

    // Execution Flow-related Methods

    /**
     * Send the Request
     *
     * @return \Next\HTTP\Response|NULL
     *
     *  If an AdapterException is caught
     *  something is wrong with Response being send and thus NULL is returned
     *
     *  Otherwise, if everything is fine, a \Next\HTTP\Response instance will
     *
     * @throws \Next\HTTP\Request\RequestException
     *  No HTTP Stream Adapter provided
     *
     * @throws \Next\HTTP\Headers\Fields\FieldsException
     *  Invalid or malformed Cookie (s) Value (s)
     *
     * @see \Next\HTTP\Stream\Adapter\AdapterException
     */
    public function send() {

        // Setting Up a Fallback HTTP Stream Adapter

        if( is_null( $this -> adapter ) ) {

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
         * Cookies
         *
         * Cookies are defined in a Header Field too, so they come first
         */
        try {

            $this -> headers -> addHeader(
                $this -> cookies -> getCookies( TRUE )
            );

        } catch( FieldsException $e ) {

            throw new RequestException(

                $e -> getMessage()
            );
        }

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

        } catch( AdapterException $e ) {

            return new Response;
        }
    }

    // Accessors

    /**
     * Get Connection Adapter, available only in External Requests
     *
     * @return \Next\HTTP\Adapter\Adapter
     *  Adapter Object
     */
    public function getAdapter() {
        return $this -> adapter;
    }

    // Auxiliary Methods

    /**
     * Checks Parameter Options Integrity
     *
     * @throws \Next\HTTp\Request\RequestException
     *  Thrown if Parameter Option 'adapter', if defined, isn't
     *  an instance of \Next\HTTP\Stream\Adapter\Adapter
     */
    private function checkintegrity() {

        if( isset( $this -> options-> adapter ) ) {

            if( ! $this -> options -> adapter instanceof Adapter ) {
                throw new RequestException( 'Invalid Stream Adapter' );
            }
        }
    }

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
    private function getProtocolVersion() {

        $headers = get_headers( $this -> uri );

        if( $headers !== FALSE ) {

            $data = array_shift( $headers );

            preg_match( self::PROTOCOL_REGEXP, $data, $match );

            if( array_key_exists( 'version', $match ) ) {

                $this -> protocol -> version = $match['version'];
            }
        }
    }

    /**
     * Add Default Headers
     *
     * Default Headers comes from apache_request_headers() function (if available)
     * of by analyzing $_SERVER variable
     *
     * This is a wrapper method, in order to make Request Constructor shorter
     */
    private function addDefaultHeaders() {

        /**
         * @internal
         * These Headers are added only here, mainly but not strictly, in order to not
         * create any conflict with User-Defined Headers Choices.
         *
         * E.g:
         *
         * In order to use Google's URL Shortener API, we should define three required
         * Stream Context Params:
         *
         * - Method          => Must be POST
         * - Content-Type    => As part of headers, must be application/json
         * - POSTDATA        => A JSON string in the format {"longUrl": "LONG_URL_HERE"}
         *
         * But, among all the default headers automatically provided
         * through apache_request_headers() function -OR- by analyzing
         * $_SERVER contents, a simple Request to the Service fails
         *
         * So, if we have an HTTP Stream to work with, these Headers are not added
         */
        if( function_exists( 'apache_request_headers' ) ) {

            try {

                $this -> headers -> addHeader( apache_request_headers() );

            } catch( FieldsException $e ) {

                /**
                 * @internal
                 * We're silencing the FieldsException in order to not break the Request Flow
                 * However, if this Exception is caught, no Request Headers will be available
                 */
            }

        } else {

            // This avoids non-HTTP-related indexes to be added as Generic Header

            $fields = array_filter(

                array_keys( $_SERVER ),

                function( $item ) {
                    return ( substr( $item, 0, 5 ) == 'HTTP_' );
                }
            );

            $length = count( $fields );

            if( $length != 0 ) {

                try {

                    $this -> headers -> addHeader(

                        array_intersect_key(

                            $_SERVER,

                            array_combine( $fields, array_fill( 0, $length, NULL ) )
                        )
                    );

                } catch( FieldsException $e ) {

                    // Same explanation as above
                }
            }
        }
    }

    // Request Method-related Wrappers

    /**
     * Wrapper method for POST Request routines
     */
    private function sendPost() {

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
            if( $this -> headers -> find( 'Content-Type' ) === FALSE ) {

                try {

                    $this-> headers -> addHeader(
                        new ContentType( [ 'value' => 'application/x-www-form-urlencoded' ] )
                    );

                } catch( FieldsException $e ) {

                    // Silenced because we're 100% sure this FieldsException will never be caught :P
                }
            }
        }
    }
}