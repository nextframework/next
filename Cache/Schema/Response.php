<?php

/**
 * Caching Schema Class: HTTP Response Headers | Cache\Schema\Response.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Cache\Schema;

use Next\HTTP\Headers\Fields\Entity\ContentType;      # Content-Type Header Class
use Next\HTTP\Headers\Fields\Entity\ContentLength;    # Content-Length Header Class
use Next\HTTP\Headers\Fields\Entity\LastModified;     # Last-Modified Header Class
use Next\HTTP\Headers\Fields\Common\CacheControl;     # Cache-Control Header Class
use Next\HTTP\Headers\Fields\Response\ETag;           # ETag Header Class
use Next\HTTP\Headers\Fields\Raw;                     # Raw Header Field Class

/**
 * Defines a Caching Schema through HTTP Response Headers, sending
 * Content-Type, Content-Length, Last-Modified, Cache-Control and E-Tag
 * Header Fields for resources that are allowed to be cached.
 *
 * It's also responsible to send 304 and 404 Headers when given
 * resource is, respectively, already cached or not found
 *
 * @package    Next\Cache\Schema
 */
class Response extends AbstractSchema {

    /**
     * Default MIME Type
     *
     * @var string
     */
    const DEFAULT_CTYPE = 'text/plain';

    /**
     * Default Options
     *
     * @var array $defaultOptions
     */
    protected $defaultOptions = [

        'cacheable'  => [

            // Images

            'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico',

            // Fonts

            'eot', 'otf', 'ttf', 'woff', 'woff2',

            // Static Files

            'cur', 'map', 'js', 'json', 'css', 'txt', 'xml'
        ],

        'skip' => []
    ];

    /**
     * Cacheable Extension => MIME-Type List
     *
     * @var array $mime
     */
    private $mime = [

        // Images

        'png' => 'image/png', 'jpg' => 'image/jpeg',    'jpeg' => 'image/jpeg',
        'gif' => 'image/gif', 'svg' => 'image/svg+xml', 'ico'  => 'image/x-icon',

        // Fonts

        'eot' => 'application/vnd.ms-fontobject',   'otf'   => 'font/opentype',
        'ttf' => 'font/ttf', 'woff' => 'font/woff', 'woff2' => 'font/woff2',

        // Static Files

        'cur'  => 'application/x-win-bitmap',
        'map'  => 'application/json', 'js' => 'application/javascript',
        'json' => 'application/json', 'css' => 'text/css', 'txt' => 'text/plain',
        'xml'  => 'application/xml'
    ];

    /**
     * Caching Routine to be executed by \Next\Controller\Front
     */
    public function run() {

        $URI = $this -> application -> getRequest() -> getRequestURI();

        $extensions = array_merge(
            (array) $this -> options -> cacheable, (array) $this -> options -> skip
        );

        $regexp = sprintf( '/\.(%s)(\?.*?)?$/', implode( '|', $extensions ) );

        if( preg_match( $regexp, $URI, $matches ) ) {

            // Should we cache current Request?

            if( isset( $matches[ 1 ] ) && in_array( $matches[ 1 ], (array) $this -> options -> skip ) ) {

                /**
                 * @internal
                 *
                 * If not we'll tell Application's Router to abort its flow,
                 * so the FrontController can keep going
                 */
                $this -> application -> getRouter() -> abortFlow();

                return;
            }

            $response = $this -> application -> getResponse();

            /**
             * @internal
             *
             * Removing all contents after URI, like static files
             * versioning (i.e. file.css?v=1.2.3) so it can be found by
             * PHP's file functions and thus filemtime won't file (hopefully >.<)
             */
            if( isset( $matches[ 2 ] ) ) {
                $URI = str_replace( $matches[ 2 ], '', $URI );
            }

            // File not found

            if( ! file_exists( $URI ) ) {

                $response -> addHeader(
                    new Raw( [ 'value' => 'HTTP/1.1 404 Not Found' ] )
                );

                $response -> send();
            }

            // Timestamp of last modification

            $lm = filemtime( $URI );

            $etag = md5_file( $URI );

            $response -> addHeader(

                new LastModified(
                    [ 'value' => sprintf( '%s GMT', gmdate( 'D, d M Y H:i:s', $lm ) ) ]
                )
            );

            $response -> addHeader( new ETag( [ 'value' => $etag ] ) )
                      -> addHeader( new CacheControl( [ 'value' => 'public' ] ) );

            // Ugliest shortening ever >.<

            $HIMS = ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : FALSE );
            $HINM = ( isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) ? trim( $_SERVER['HTTP_IF_NONE_MATCH'] ) : FALSE );

            if( $HIMS !== FALSE && strtotime( $HIMS ) == $lm ) {

                // File has not changed

                $response -> addHeader(
                    new Raw( [ 'value' => 'HTTP/1.1 304 Not Modified' ] )
                );

            } elseif( $HINM !== FALSE && $HINM == $etag ) {

                // File has not changed

                $response -> addHeader(
                    new Raw( [ 'value' => 'HTTP/1.1 304 Not Modified' ] )
                );

            } else  {

                $data = file_get_contents( $URI );

                if( array_key_exists( $matches[ 1 ], $this -> mime ) ) {

                    $response -> addHeader(

                        new ContentType(
                            [ 'value' => $this -> mime[ $matches[ 1 ] ] ]
                        )
                    );

                } else {

                    $response -> addHeader(
                        new ContentType( [ 'value' => self::DEFAULT_CTYPE ] )
                    );
                }

                $response -> cleanupMarkup( FALSE )
                          -> addHeader(
                                new ContentLength(

                                    [ 'value' => strlen( $data ) ]
                                )
                             )
                          -> appendBody( $data );
            }

            $response -> send();
        }
    }
}