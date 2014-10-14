<?php

namespace Next\HTTP;

use Next\HTTP\Response\ResponseException;          # Response Exception
use Next\HTTP\Headers\Fields\FieldsException;      # Headers Fields Exception Class
use Next\Components\Object;                        # Object Class
use Next\Components\Invoker;                       # Invoker Class
use Next\HTTP\Request;                             # Request Class
use Next\HTTP\Headers\Fields\Response\Location;    # Location Header Field
use Next\HTTP\Headers\Fields\Raw;                  # Raw Data Header Field
use Next\HTTP\Headers\Fields\Generic;              # Generic Data Header Field

/**
 * Response Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Response extends Object {

    // HTTP Status Codes for Request Status Comparison and reference only

    // [ Informational 1xx ]

    /**
     * HTTP_CONTINUE
     *
     * @var integer
     */
    const HTTP_CONTINUE                          = 100;

    /**
     * SWITCHING_PROTOCOLS
     *
     * @var integer
     */
    const SWITCHING_PROTOCOLS                    = 101;

    /**
     * PROCESSING
     *
     * @var integer
     */
    const PROCESSING                             = 102; // WebDAV (RFC 2518)

    // [ Successful 2xx ]

    /**
     * OK
     *
     * @var integer
     */
    const OK                                     = 200;

    /**
     * CREATED
     *
     * @var integer
     */
    const CREATED                                = 201;

    /**
     * ACCEPTED
     *
     * @var integer
     */
    const ACCEPTED                               = 202;

    /**
     * NONAUTHORITATIVE_INFORMATION
     *
     * @var integer
     */
    const NONAUTHORITATIVE_INFORMATION           = 203;

    /**
     * NO_CONTENT
     *
     * @var integer
     */
    const NO_CONTENT                             = 204;

    /**
     * RESET_CONTENT
     *
     * @var integer
     */
    const RESET_CONTENT                          = 205;

    /**
     * PARTIAL_CONTENT
     *
     * @var integer
     */
    const PARTIAL_CONTENT                        = 206;

    /**
     * MULTI_STATUS
     *
     * @var integer
     */
    const MULTI_STATUS                           = 207; // WebDAV (RFC 4918)

    /**
     * ALREADY_REPORTED
     *
     * @var integer
     */
    const ALREADY_REPORTED                       = 208; // WebDAV (RFC 5842)

    /**
     * IM_USED
     *
     * @var integer
     */
    const IM_USED                                = 226; // RFC 3229

    // [ Redirection 3xx ]

    /**
     * MULTIPLE_CHOICES
     *
     * @var integer
     */
    const MULTIPLE_CHOICES                       = 300;

    /**
     * MOVED_PERMANENTLY
     *
     * @var integer
     */
    const MOVED_PERMANENTLY                      = 301;

    /**
     * FOUND
     *
     * @var integer
     */
    const FOUND                                  = 302;

    /**
     * SEE_OTHER
     *
     * @var integer
     */
    const SEE_OTHER                              = 303;

    /**
     * NOT_MODIFIED
     *
     * @var integer
     */
    const NOT_MODIFIED                           = 304;

    /**
     * USE_PROXY
     *
     * @var integer
     */
    const USE_PROXY                              = 305;

    /**
     * UNUSED
     *
     * @var integer
     */
    const UNUSED                                 = 306;

    /**
     * TEMPORARY_REDIRECT
     *
     * @var integer
     */
    const TEMPORARY_REDIRECT                     = 307;

    /**
     * PERMANENT_REDIRECT
     *
     * @var integer
     */
    const PERMANENT_REDIRECT                     = 308; // Experimental

    // [ Client Error 4xx ]

    /**
     * BAD_REQUEST
     *
     * @var integer
     */
    const BAD_REQUEST                            = 400;

    /**
     * UNAUTHORIZED
     *
     * @var integer
     */
    const UNAUTHORIZED                           = 401;

    /**
     * PAYMENT_REQUIRED
     *
     * @var integer
     */
    const PAYMENT_REQUIRED                       = 402;

    /**
     * FORBIDDEN
     *
     * @var integer
     */
    const FORBIDDEN                              = 403;

    /**
     * NOT_FOUND
     *
     * @var integer
     */
    const NOT_FOUND                              = 404;

    /**
     * METHOD_NOT_ALLOWED
     *
     * @var integer
     */
    const METHOD_NOT_ALLOWED                     = 405;

    /**
     * NOT_ACCEPTABLE
     *
     * @var integer
     */
    const NOT_ACCEPTABLE                         = 406;

    /**
     * PROXY_AUTHENTICATION_REQUIRED
     *
     * @var integer
     */
    const PROXY_AUTHENTICATION_REQUIRED          = 407;

    /**
     * REQUEST_TIMEOUT
     *
     * @var integer
     */
    const REQUEST_TIMEOUT                        = 408;

    /**
     * CONFLICT
     *
     * @var integer
     */
    const CONFLICT                               = 409;

    /**
     * GONE
     *
     * @var integer
     */
    const GONE                                   = 410;

    /**
     * LENGTH_REQUIRED
     *
     * @var integer
     */
    const LENGTH_REQUIRED                        = 411;

    /**
     * PRECONDITION_FAILED
     *
     * @var integer
     */
    const PRECONDITION_FAILED                    = 412;

    /**
     * REQUEST_ENTITY_TOO_LARGE
     *
     * @var integer
     */
    const REQUEST_ENTITY_TOO_LARGE               = 413;

    /**
     * REQUEST_URI_TOO_LONG
     *
     * @var integer
     */
    const REQUEST_URI_TOO_LONG                   = 414;

    /**
     * UNSUPPORTED_MEDIA_TYPE
     *
     * @var integer
     */
    const UNSUPPORTED_MEDIA_TYPE                 = 415;

    /**
     * REQUESTED_RANGE_NOT_SATISFIABLE
     *
     * @var integer
     */
    const REQUESTED_RANGE_NOT_SATISFIABLE        = 416;

    /**
     * EXPECTATION_FAILED
     *
     * @var integer
     */
    const EXPECTATION_FAILED                     = 417;

    /**
     * IM_TEAPOT
     *
     * @var integer
     */
    const IM_TEAPOT                              = 418;

    /**
     * ENHANCE_YOUR_CALM
     *
     * @var integer
     */
    const ENHANCE_YOUR_CALM                      = 420; //  Twitter

    /**
     * UNPROCESSABLE_ENTITY
     *
     * @var integer
     */
    const UNPROCESSABLE_ENTITY                   = 422; //  WebDAV (RFC 4918)

    /**
     * LOCKED
     *
     * @var integer
     */
    const LOCKED                                 = 423; //  WebDAV (RFC 4918)

    /**
     * FAILED_DEPENDENCY
     *
     * @var integer
     */
    const FAILED_DEPENDENCY                      = 424; //  WebDAV (RFC 4918)

    //const METHOD_FAILURE                       = 424; //  WebDAV - Duplicated?

    /**
     * UNORDERED_COLLECTION
     *
     * @var integer
     */
    const UNORDERED_COLLECTION                   = 425; //  Internet Draft

    /**
     * UPGRADE_RREQUIRED
     *
     * @var integer
     */
    const UPGRADE_RREQUIRED                      = 426; //  RFC 2817

    /**
     * PRECONDITION_REQUIRED
     *
     * @var integer
     */
    const PRECONDITION_REQUIRED                  = 428; //  RFC 6585

    /**
     * TOO_MANU_REQUESTS
     *
     * @var integer
     */
    const TOO_MANU_REQUESTS                      = 429; //  RFC 6585

    /**
     * REQUEST_HEADER_FIELDS_TOO_LARGE
     *
     * @var integer
     */
    const REQUEST_HEADER_FIELDS_TOO_LARGE        = 431; //  RFC 6585

    /**
     * NO_RESPONSE
     *
     * @var integer
     */
    const NO_RESPONSE                            = 444; //  Nginx

    /**
     * RETRY_WITH
     *
     * @var integer
     */
    const RETRY_WITH                             = 449; //  Microsoft

    /**
     * BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS
     *
     * @var integer
     */
    const BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS   = 450; //  Microsoft

    /**
     * UNAVAILABLE_FOR_LEGAL_REASONS
     *
     * @var integer
     */
    const UNAVAILABLE_FOR_LEGAL_REASONS          = 451; //  Internet Draft

    /**
     * REQUEST_HEADER_TOO_LARGE
     *
     * @var integer
     */
    const REQUEST_HEADER_TOO_LARGE               = 494; //  Nginx

    /**
     * CERT_ERROR
     *
     * @var integer
     */
    const CERT_ERROR                             = 495; //  Nginx

    /**
     * NO_CERT
     *
     * @var integer
     */
    const NO_CERT                                = 496; //  Nginx

    /**
     * TO_HTTPS
     *
     * @var integer
     */
    const TO_HTTPS                               = 497; //  Nginx

    /**
     * CLIENT_CLOSED_REQUEST
     *
     * @var integer
     */
    const CLIENT_CLOSED_REQUEST                  = 499; //  Nginx

    // [ Server Error 5xx ]

    /**
     * INTERNAL_SERVER_ERROR
     *
     * @var integer
     */
    const INTERNAL_SERVER_ERROR                  = 500;

    /**
     * NOT_IMPLEMENTED
     *
     * @var integer
     */
    const NOT_IMPLEMENTED                        = 501;

    /**
     * BAD_GATEWAY
     *
     * @var integer
     */
    const BAD_GATEWAY                            = 502;

    /**
     * SERVICE_UNAVAILABLE
     *
     * @var integer
     */
    const SERVICE_UNAVAILABLE                    = 503;

    /**
     * GATEWAY_TIMEOUT
     *
     * @var integer
     */
    const GATEWAY_TIMEOUT                        = 504;

    /**
     * VERSION_NOT_SUPPORTED
     *
     * @var integer
     */
    const VERSION_NOT_SUPPORTED                  = 505;

    /**
     * INSUFFICIENT_STORAGE
     *
     * @var integer
     */
    const INSUFFICIENT_STORAGE                   = 507; //  WebDAV (RFC 4918)

    /**
     * LOOP_DETECTED
     *
     * @var integer
     */
    const LOOP_DETECTED                          = 508; //  WebDAV (RFC 5842)

    /**
     * BANDWIDTH_LIMIT_EXCEEDED
     *
     * @var integer
     */
    const BANDWIDTH_LIMIT_EXCEEDED               = 509; //  Apache bw/limited extension

    /**
     * NOT_EXTENDED
     *
     * @var integer
     */
    const NOT_EXTENDED                           = 510; //  RFC 2774

    /**
     * NETWORK_AUTHENTICATION_REQUIRED
     *
     * @var integer
     */
    const NETWORK_AUTHENTICATION_REQUIRED        = 511; //  RFC 6585

    /**
     * NETWORK_READ_TIMEOUT_ERROR
     *
     * @var integer
     */
    const NETWORK_READ_TIMEOUT_ERROR             = 598; //  Unknown

    /**
     * NETWORK_CONNECT_TIMEOUT_ERROR
     *
     * @var integer
     */
    const NETWORK_CONNECT_TIMEOUT_ERROR          = 599; //  Unknown

    /**
     * Blank Lines Regexp
     *
     * Match two blank lines in a row allowing it to be replaced
     * by a single one, cleaning the Response Body
     *
     * @var string
     */
    const CLEANUP = '/(^[\\r\\n]*|[\\r\\n]{2,})[\\s\\t]*[\\r\\n]+/';

    /**
     * HTTP Message for Codes
     *
     * @var array $messages
     */
    private $messages = array (

        // [ Informational 1xx ]

        self::HTTP_CONTINUE                           => 'Continue',
        self::SWITCHING_PROTOCOLS                     => 'Switching Protocols',
        self::PROCESSING                              => 'Processing',

        // [ Successful 2xx ]

        self::OK                                      => 'OK',
        self::CREATED                                 => 'Created',
        self::ACCEPTED                                => 'Accepted',
        self::NONAUTHORITATIVE_INFORMATION            => 'Non-Authoritative Information',
        self::NO_CONTENT                              => 'No Content',
        self::RESET_CONTENT                           => 'Reset Content',
        self::PARTIAL_CONTENT                         => 'Partial Content',
        self::MULTI_STATUS                            => 'Multiple Status',
        self::ALREADY_REPORTED                        => 'Already Reported',
        self::IM_USED                                 => 'IM Used',

        // [ Redirection 3xx ]

        self::MULTIPLE_CHOICES                        => 'Multiple Choices',
        self::MOVED_PERMANENTLY                       => 'Moved Permanently',
        self::FOUND                                   => 'Found',
        self::SEE_OTHER                               => 'See Other',
        self::NOT_MODIFIED                            => 'Not Modified',
        self::USE_PROXY                               => 'Use Proxy',
        self::UNUSED                                  => '(Unused)',
        self::TEMPORARY_REDIRECT                      => 'Temporary Redirect',
        self::PERMANENT_REDIRECT                      => 'Permanent Redirect',

        // [ Client Error 4xx ]

        self::BAD_REQUEST                             => 'Bad Request',
        self::UNAUTHORIZED                            => 'Unauthorized',
        self::PAYMENT_REQUIRED                        => 'Payment Required',
        self::FORBIDDEN                               => 'Forbidden',
        self::NOT_FOUND                               => 'Not Found',
        self::METHOD_NOT_ALLOWED                      => 'Method Not Allowed',
        self::NOT_ACCEPTABLE                          => 'Not Acceptable',
        self::PROXY_AUTHENTICATION_REQUIRED           => 'Proxy Authentication Required',
        self::REQUEST_TIMEOUT                         => 'Request Timeout',
        self::CONFLICT                                => 'Conflict',
        self::GONE                                    => 'Gone',
        self::LENGTH_REQUIRED                         => 'Length Required',
        self::PRECONDITION_FAILED                     => 'Precondition Failed',
        self::REQUEST_ENTITY_TOO_LARGE                => 'Request Entity Too Large',
        self::REQUEST_URI_TOO_LONG                    => 'Request-URI Too Long',
        self::UNSUPPORTED_MEDIA_TYPE                  => 'Unsupported Media Type',
        self::REQUESTED_RANGE_NOT_SATISFIABLE         => 'Requested Range Not Satisfiable',
        self::EXPECTATION_FAILED                      => 'Expectation Failed',
        self::IM_TEAPOT                               => 'I\'m a teapot',
        self::ENHANCE_YOUR_CALM                       => 'Enhance your Calm',
        self::UNPROCESSABLE_ENTITY                    => 'Unprocessable Entity',
        self::LOCKED                                  => 'Locked',
        self::FAILED_DEPENDENCY                       => 'Failed Dependency',
        self::UNORDERED_COLLECTION                    => 'Unordered Collection',
        self::UPGRADE_RREQUIRED                       => 'Upgrade Required',
        self::PRECONDITION_REQUIRED                   => 'Precondition Required',
        self::TOO_MANU_REQUESTS                       => 'Too Many Requests',
        self::REQUEST_HEADER_FIELDS_TOO_LARGE         => 'Request Header Fields Too Large',
        self::NO_RESPONSE                             => 'No Response',
        self::RETRY_WITH                              => 'Retry With',
        self::BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS    => 'Blocked by Windows Parental Controls',
        self::UNAVAILABLE_FOR_LEGAL_REASONS           => 'Unavailable For Legal Reasons',
        self::REQUEST_HEADER_TOO_LARGE                => 'Request Header Too Large',
        self::CERT_ERROR                              => 'Cert Error',
        self::NO_CERT                                 => 'No Cert',
        self::TO_HTTPS                                => 'HTTP to HTTPS',
        self::CLIENT_CLOSED_REQUEST                   => 'Client Closed Request',

        // [ Server Error 5xx ]

        self::INTERNAL_SERVER_ERROR                   => 'Internal Server Error',
        self::NOT_IMPLEMENTED                         => 'Not Implemented',
        self::BAD_GATEWAY                             => 'Bad Gateway',
        self::SERVICE_UNAVAILABLE                     => 'Service Unavailable',
        self::GATEWAY_TIMEOUT                         => 'Gateway Timeout',
        self::VERSION_NOT_SUPPORTED                   => 'HTTP Version Not Supported',
        self::INSUFFICIENT_STORAGE                    => 'Insufficient Storage',
        self::LOOP_DETECTED                           => 'Loop Detected',
        self::BANDWIDTH_LIMIT_EXCEEDED                => 'Bandwidth Limit Exceeded',
        self::NOT_EXTENDED                            => 'Not Extended',
        self::NETWORK_AUTHENTICATION_REQUIRED         => 'Network Authentication Required',
        self::NETWORK_READ_TIMEOUT_ERROR              => 'Network read timeout error',
        self::NETWORK_CONNECT_TIMEOUT_ERROR           => 'Network connect timeout error',
    );

    /**
     * Response Body
     *
     * @var string $body
     */
    private $body;

    /**
     * Headers Management Object
     *
     * @var Next\HTTP\ResponseHeaders $headers
     */
    private $headers;

    /**
     * HTTP Response Status Code
     *
     * @var integer $statusCode
     */
    private $statusCode;

    /**
     * Should we return the Response instead of send it?
     *
     * @var boolean $shouldReturn
     */
    private $shouldReturn = FALSE;

    /**
     * Should we abort the execution flow with an "exit"?
     *
     * @var boolean $shouldAbort
     */
    private $shouldAbort = TRUE;

    /**
     * Should we avoid Response Headers to be sent?
     *
     * @var boolean $disableSendingHeaders
     */
    private $disableSendingHeaders = FALSE;

    /**
     * Response Constructor
     *
     * @param string|optional $data
     *   Response Data
     *
     * @param array|optional $metaData
     *   Response Meta Data
     */
    public function __construct( $data = NULL, array $metaData = array() ) {

        parent::__construct();

        // Headers Management Object

        $this -> headers = new Headers\ResponseHeaders;

        // Extend Object Context to Headers', Cookies and Browser Classes

        $this -> extend( new Invoker( $this, $this -> headers ) );

        /**
         * @internal
         * When dealing with Routed (internal) Requests, no data will
         * be available because the Front Controller did not send the
         * Response yet.
         *
         * In External Requests, the Response is not sent anyways,
         * so we condition the buffer length to not hide any output
         * manually added, like a debugging purposes var_dump()
         */
        if( empty( $data ) || ob_get_length() ) {

            if( defined( 'TURBO_MODE' ) && TURBO_MODE === FALSE ) {

                if( function_exists( 'apache_response_headers' ) ) {

                    try {

                        $this -> headers
                              -> addHeader( apache_response_headers() );

                    } catch( FieldsException $e ) {

                        /**
                         * @internal
                         * We're silencing the FieldsException in order to not
                         * break the Response Flow
                         *
                         * However, if this Exception is caught, no Response
                         * Headers will be available
                         */
                    }

                } else {

                    $headers = headers_list();

                    foreach( $headers as $data ) {

                        list( $header, $value ) = explode( ':', $data, 2 );

                        try {

                           $this -> headers -> addHeader( $header, $value );

                        } catch( FieldsException $e ) {

                           // Same explanation as above
                        }
                    }
                }
            }

        } else {

            $this -> appendBody( $data );

            if( count( $metaData ) != 0 &&
                    ( defined( 'TURBO_MODE' ) && TURBO_MODE === FALSE ) ) {

                $this -> addResponseData( $metaData );
            }
        }
    }

    // Response Content-related Methods

    /**
     * Append content in the end of Response Body
     *
     * @param string $content
     *   Content to be appended to Response Body
     *
     * @return Next\HTTP\Response
     *   Response Object (Fluent Interface)
     */
    public function appendBody( $content ) {

        $this -> body .= $content;

        return $this;
    }

    /**
     * Prepend content in the beginning of Response Body
     *
     * @param string $content
     *   Content to be prepended to Response Body
     *
     * @return Next\HTTP\Response
     *   Response Object (Fluent Interface)
     */
    public function prependBody( $content ) {

        $this -> body = $content . $this -> body;

        return $this;
    }

    /**
     * Clear Response Body
     *
     * @return Next\HTTP\Response
     */
    public function clearBody() {

        $this -> body = NULL;

        return $this;
    }

    // Execution Flow-related Methods

    /**
     * Send the Response
     */
    public function send() {

        // Cleaning Up HTML's extra NewLines

        $this -> body = preg_replace( self::CLEANUP, "\n\n", $this -> body );

        // Fixing XHTML end tag position

        $this -> body = preg_replace( '/\s*>/m', '>', $this -> body );

        // Should we return the Response...

        if( $this -> shouldReturn ) {

            return $this;

        } else {

            // ... or flush it?

            ob_start();

            // Sending Headers

            if( ! $this -> disableSendingHeaders ) {
                $this -> sendHeaders();
            }

            echo $this -> body;

            // Should we end the Execution Flow?

            if( $this -> shouldAbort ) {
                exit;
            }

            // If we shouldn't, let's flush the buffer

            if( ob_get_length() ) { ob_end_flush(); }
        }
    }

    /**
     * Send a Redirect Header
     *
     * @param string $url
     *   URL to be Redirected
     *
     * @throws Next\HTTP\Response\ResponseException
     *   Any header was already sent
     */
    public function redirect( $url ) {

        self::canSendHeaders();

        $url = (string) $url;

        try {

            // Location Header should be sent ONLY with Absolute URIs

            $this -> headers -> addHeader( new Location( $url ) );

        } catch( FieldsException $e ) {

            /**
             *  If an FieldsException is caught, we have a Relative URI so
             *  we'll send it as a Raw Header
             */

            $this -> headers -> addHeader(

                new Raw( sprintf( 'Location: %s', $url ) )
            );
        }

        // Sending the Response Immediately

        $this -> send();
    }

    /**
     * Get current state of Response returning flag
     *
     * @return boolean
     *   Response returning flag value
     */
    public function shouldReturn() {
        return $this -> shouldReturn;
    }

    /**
     * Change state of Response return conditional flag
     *
     * @param boolean $flag
     *   New state for the flag
     *
     * @return Next\HTTP\Response
     *   Response Instance (Fluent Interface)
     */
    public function returnResponse( $flag ) {

        $this -> shouldReturn = (bool) $flag;

        return $this;
    }

    /**
     * Get current state of Response Flow abortion flag
     *
     * @return boolean
     *   Response Flow Abortion flag value
     */
    public function shouldAbort() {
        return $this -> shouldAbort;
    }

    /**
     * Change state of Response Flow Abortion flag
     *
     * @param boolean $flag
     *   New state for the flag
     *
     * @return Next\HTTP\Response
     *   Response Instance (Fluent Interface)
     */
    public function abortFlow( $flag ) {

        $this -> shouldAbort = (bool) $flag;

        return $this;
    }

    /**
     * Get current state of Headers Sending flag
     *
     * @return boolean
     *   Headers Sending flag value
     */
    public function shouldSendHeaders() {
        return $this -> disableSendingHeaders;
    }

    /**
     * Change state of Headers Sending flag
     *
     * @param boolean $flag
     *   New state for the flag
     *
     * @return Next\HTTP\Response
     *   Response Instance (Fluent Interface)
     */
    public function disableSendingHeaders( $flag ) {

        $this -> disableSendingHeaders = (bool) $flag;

        return $this;
    }

    /**
     * Check if we can send any header
     *
     * @return boolean
     *
     *   <p>TRUE if any Header was already sent and FALSE otherwise.</p>
     *
     *   <p>
     *      If argument is set to TRUE, however, an Exception will be
     *      thrown instead
     *   </p>
     *
     * @throws Next\HTTP\Response\ResponseException
     *   Any header was already sent
     */
    public static function canSendHeaders() {

        if( headers_sent( $file, $line ) ) {

            throw ResponseException::logic(

                'Cannot modify headers Information.
                Header was already sent in file <strong>%s</strong> at line <strong>%s</strong>',

                array( $file, $line )
            );

            return FALSE;
        }

        return TRUE;
    }

    /**
     * Add response Header to be sent
     *
     * This method intermediates Next\HTTP\Headers\AbstractHeaders::addHeader()
     * in order to use the proper field for HTTP Response Status Code Headers
     *
     * @param mixed $header
     *
     *   <p>Header name. Possible values are:</p>
     *
     *   <p>
     *
     *       <ul>
     *
     *           <li>
     *
     *               <p>An integer as HTTP Response State Header</p>
     *
     *               <p>In this case a Raw Header Field will be sent</p>
     *           </li>
     *
     *           <li>
     *
     *               <p>
     *                   A well-formed Header Field, instance of
     *                   Next\HTTP\Headers\Fields\Field
     *               </p>
     *
     *               <p>In this case it'll be used "as is"</p>
     *
     *           </li>
     *
     *           <li>
     *
     *               <p>A Header Field Name</p>
     *
     *               <p>
     *                   We'll try to match a valid one or send
     *                   it as "Generic"
     *               </p>
     *
     *           </li>
     *
     *           <li>
     *
     *               <p>
     *                   An array of Header Fields, where each value
     *                   can be a well-formed Header Field or a
     *                   Header Field Name
     *               </p>
     *
     *               <p>In this case we'll add them recursively</p>
     *
     *           </li>
     *
     *       </ul>
     *
     *   </p>
     *
     * @param string|optional $value
     *   Header value
     *
     * @param float $schemaVersion
     *   Schema Version. Only used if <strong>$header</strong> is an integer
     *
     * @return Next\HTTP\Response
     *   Response Object (Fluent Interface)
     */
    public function addHeader( $header, $value = NULL, $schemaVersion = 1.1 ) {

        // Is it an HTTP State Header?

        if( is_int( $header ) && in_array( $header, array_keys( $this -> messages ) ) ) {

           try {

                $this -> headers -> addHeader(

                    new Raw(

                        sprintf(

                            'HTTP/%s %d %s', $schemaVersion, $header,

                            $this -> messages[ $header ]
                        )
                    )
                );

           } catch( FieldsException $e ) {}

        } else {

           try {

               $this -> headers -> addHeader( $header, $value );

           } catch( FieldsException $e  ) {}
        }

        return $this;
    }

    // Accessors

        // Response Body-related Methods

    /**
     * Check whether or not Response Body is empty
     *
     * @return boolean
     *   TRUE if Response Body is not empty and FALSE otherwise
     */
    public function hasBody() {
        return (bool) ! empty( $this -> body );
    }

    /**
     * Get Response Body
     *
     * @return string
     *   The Response Body
     */
    public function getBody() {
        return $this -> body;
    }

    // Information-related Methods

    /**
     * Does the status code indicate a client error?
     *
     * @return bool
     *   TRUE if HTTP Response Code is in Client Error Range and FALSE otherwise
     */
    public function isClientError() {
        return ( $this -> statusCode <  self::INTERNAL_SERVER_ERROR &&
                 $this -> statusCode >= self::BAD_REQUEST );
    }

    /**
     * Is a Forbidden Request?
     *
     * @return bool
     *   TRUE if HTTP Response Code is equal to Forbidden Response Code
     *   and FALSE otherwise
     */
    public function isForbidden() {
        return ( self::FORBIDDEN == $this -> statusCode );
    }

    /**
     * Is the current status informational?
     *
     * @return bool
     *   TRUE if HTTP Response Code is in Informational Range
     *   and FALSE otherwise
     */
    public function isInformational() {
        return ( $this -> statusCode >= self::HTTP_CONTINUE && $this -> statusCode < self::OK );
    }

    /**
     * Does the status code indicate the resource is not found?
     *
     * @return bool
     *   TRUE if HTTP Response Code is equal to Not Found Response Code
     *   and FALSE otherwise
     */
    public function isNotFound() {
        return ( self::NOT_FOUND === $this -> statusCode );
    }

    /**
     * Do we have a normal / OK response?
     *
     * @return bool
     *   TRUE if HTTP Response Code is equal to Success/OK Response Code
     *   and FALSE otherwise
     */
    public function isOk() {
        return ( self::OK === $this -> statusCode );
    }

    /**
     * Does the status code reflects a server error?
     *
     * @return bool
     *   TRUE if HTTP Response Code is in Server Error Range and FALSE otherwise
     */
    public function isServerError() {

        return ( self::INTERNAL_SERVER_ERROR <= $this -> statusCode &&
            600 > $this -> statusCode );
    }

    /**
     * Do we have a redirect?
     *
     * @return bool
     *   TRUE if HTTP Response Code is in Redirection Range and FALSE otherwise
     */
    public function isRedirect() {

        return ( self::MULTIPLE_CHOICES <= $this -> statusCode &&
            self::BAD_REQUEST > $this -> statusCode );
    }

    /**
     * Was the response successful?
     *
     * @return bool
     *   TRUE if HTTP Response Code is in Success Range and FALSE otherwise
     */
    public function isSuccess() {

        return ( self::OK <= $this -> statusCode &&
            self::MULTIPLE_CHOICES > $this -> statusCode );
    }

    /**
     * Get HTTP Status Code
     *
     * @return integer|NULL
     *   HTTP Response Code, if found and NULL otherwise
     */
    public function getStatusCode() {
        return $this -> statusCode;
    }

    // Auxiliary Methods

    /**
     * Send Response Headers
     *
     * @throws Next\HTTP\Response\ResponseException
     *   Any header was already sent
     */
    private function sendHeaders() {

        /**
         * @internal
         * Can we send the headers?
         * We force the Exception to be thrown in order to keep
         * Framework's integrity
         */
        self::canSendHeaders();

        foreach( $this -> headers -> getHeaders() as $header ) {

            /**
             * @internal
             * Generic Headers are a little different
             *
             * If we use the Header String Representation, all Generic Headers
             * would be sent as 'Generic', and if we have more than one
             * Generic Header, all the previous would be overwritten.
             */
            if( $header instanceof Generic ) {

                list( $name, $value ) = explode( ':', $header -> getValue() );

                header( sprintf( '%s: %s', $name, trim( $value ) ) );

            } elseif( $header instanceof Raw ) {

                // Raw Headers. Sent "as is"

                header( $header -> getValue() );

            } else {

                // Well formed Header? Let's use its string representation

                header( (string) $header );
            }
        }
    }

    /**
     * Fill Response Information
     *
     * @param array $meta
     *   Response Metadata
     */
    private function addResponseData( array $meta ) {

        // Shortening Wrapper Data Metadata

        $wrapperData = ( isset( $meta['wrapper_data'] ) ? $meta['wrapper_data'] : NULL );

        // Default, for PHP error prevention

        $code = NULL;

        /**
         * @internal
         * Response Headers are the same array of Wrapper Data, but
         * if we have the HTTP Status Code in the first index, the
         * Headers themselves start from the second index
         *
         * If HTTP Response Code is missing, they start from first index
         */
        $headers = $wrapperData;

        if( ! is_null( $wrapperData ) && count( $wrapperData ) != 0 ) {

            // HTTP Status Code

            /**
             * @internal
             * Simple way to get HTTP Status Code:
             * From the end, three digits before any text
             */
            preg_match( '/ (\d{3}) .*?$/', $wrapperData[ 0 ], $code );

            if( count( $code ) != 0 ) {

                $this -> statusCode =& $code[ 1 ];

                /**
                 *  If we got the HTTP Status Header, let's remove its
                 *  index from Headers array
                 */
                array_shift( $headers );
            }

            // Response Headers

            if( count( $headers ) != 0 ) {

                foreach( $headers as $data ) {

                    list( $header, $value ) = explode( ':', $data, 2 );

                    $this -> addHeader( $header, $value );
                }
            }
        }
    }

    public function __toString() {

        $this -> send(); return '';
    }
}