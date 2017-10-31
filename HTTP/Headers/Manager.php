<?php

/**
 * HTTP Headers Abstract Class | HTTP\Headers\AbstractHeaders.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers;

use Next\HTTP\Headers\Field;              # Header Interface

use Next\Components\Object;               # Object Class
use Next\Components\Invoker;              # Invoker Class
use Next\Components\Collections\Lists;    # Lists Class

use Next\HTTP\Headers\Generic;            # Generic Header Field Class
use Next\HTTP\Headers\Raw;                # Raw Header Field Class

/**
 * The HTTP Headers Manager manages a Lists Collection where all
 * HTTP Header Fields Objects are stored offering a bridged access to it,
 * so the Headers can be accessed directly
 *
 * However, currently, only through Object syntax since the Extended Context
 * feature doesn't provide access to the \ArrayAccess Interface implemented
 * by the Lists Collection Class
 *
 * @package    Next\HTTP
 *
 * @uses       Next\HTTP\Headers\Field
 *             Next\Components\Object
 *             Next\Components\Invoker
 *             Next\Components\Collections\Lists
 *             Next\HTTP\Headers\Generic
 *             Next\HTTP\Headers\Raw
 *             Iterator
 */
class Manager extends Object {

    /**
     * Header Fields Namespace
     *
     * @var string
     */
    const FIELDS_NS = '\Next\HTTP\Headers';

    /**
     * Headers List
     *
     * @var \Next\Components\Collections\Lists $headers
     */
    private $headers;

    /**
     * Known Headers
     *
     * @var array $known
     */
    private $known = [

        // Common Headers

        'CacheControl'        => 'Common\CacheControl',
        'Connection'          => 'Common\Connection',
        'Date'                => 'Common\Date',
        'Pragma'              => 'Common\Pragma',
        'Trailer'             => 'Common\Trailer',
        'TransferEncoding'    => 'Common\TransferEncoding',
        'Upgrade'             => 'Common\Upgrade',
        'Via'                 => 'Common\Via',
        'Warning'             => 'Common\Warning',


        // Entity Headers

        'Allow'               => 'Entity\Allow',
        'ContentEncoding'     => 'Entity\ContentEncoding',
        'ContentLanguage'     => 'Entity\ContentLanguage',
        'ContentLength'       => 'Entity\ContentLength',
        'ContentLocation'     => 'Entity\ContentLocation',
        'ContentMD5'          => 'Entity\ContentMD5',
        'ContentRange'        => 'Entity\ContentRange',
        'ContentType'         => 'Entity\ContentType',
        'Expires'             => 'Entity\Expires',
        'LastModified'        => 'Entity\LastModified',


        // Request Headers

        'Accept'              => 'Request\Accept',
        'AcceptCharset'       => 'Request\AcceptCharset',
        'AcceptEncoding'      => 'Request\AcceptEncoding',
        'AcceptLanguage'      => 'Request\AcceptLanguage',
        'Authorization'       => 'Request\Authorization',
        'Cookie'              => 'Request\Cookie',
        'DNT'                 => 'Request\DNT',
        'Expect'              => 'Request\Expect',
        'From'                => 'Request\From',
        'Host'                => 'Request\Host',
        'IfMatch'             => 'Request\IfMatch',
        'IfModifiedSince'     => 'Request\IfModifiedSince',
        'IfNoneMatch'         => 'Request\IfNoneMatch',
        'IfRange'             => 'Request\IfRange',
        'IfUnmodifiedSince'   => 'Request\IfUnmodifiedSince',
        'MaxForwards'         => 'Request\MaxForwards',
        'ProxyAuthorization'  => 'Request\ProxyAuthorization',
        'Range'               => 'Request\Range',
        'Referer'             => 'Request\Referer',
        'TE'                  => 'Request\TE',
        'UserAgent'           => 'Request\UserAgent',
        'XDoNotTrack'         => 'Request\XDoNotTrack',
        'XRequestedWith'      => 'Request\XRequestedWith',

        // Response Headers

        'AcceptRanges'        => 'Response\AcceptRanges',
        'Age'                 => 'Response\Age',
        'ContentDisposition'  => 'Response\ContentDisposition',
        'ETag'                => 'Response\ETag',
        'Link'                => 'Response\Link',
        'Location'            => 'Response\Location',
        'ProxyAuthenticate'   => 'Response\ProxyAuthenticate',
        'RetryAfter'          => 'Response\RetryAfter',
        'Server'              => 'Response\Server',
        'SetCookie'           => 'Response\SetCookie',
        'Vary'                => 'Response\Vary',
        'WWWAuthenticate'     => 'Response\WWWAuthenticate',
        'XContentTypeOptions' => 'Response\XContentTypeOptions',
        'XForwardedFor'       => 'Response\XForwardedFor',
        'XForwardedProto'     => 'Response\XForwardedProto',
        'XFrameOptions'       => 'Response\XFrameOptions',
        'XPoweredBy'          => 'Response\XPoweredBy',
        'XXSSProtection'      => 'Response\XXSSProtection',
    ];

    /**
     * Additional Initialization
     */
    protected function init() : void {

        // Setting Up Headers Lists

        $this -> headers = new Lists;

        // Extend Object Headers' List

        $this -> extend( new Invoker( $this, $this -> headers ) );
    }

    // Basic Header Manipulation

    /**
     * Add a Header
     *
     * @param mixed $header
     *  Header name. Possible values are:
     *
     *   <ul>
     *
     *       <li>An integer as HTTP Response State Header</li>
     *
     *       <li>
     *
     *           A well-formed Header Field.
     *
     *           It'll be used "as is", an instance of
     *           \Next\HTTP\Headers\Field
     *       </li>
     *
     *       <li>
     *
     *           A Header Field Name.
     *
     *           We'll try to match a valid one or send it as "Generic"
     *       </li>
     *
     *       <li>
     *
     *           An associative array of Header Fields to be added
     *           recursively, where each value can be a well-formed
     *           Header Field or a Header Field Name
     *       </li>
     *
     *   </ul>
     *
     * @param string|optional $value
     *  Header Field Value
     *
     * @return \Next\HTTP\Headers\AbstractHeaders
     *  AbstractHeaders Object (Fluent Interface)
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Invalid or mal-formed Header Value
     */
    public function addHeader( $header, $value = NULL ) : Manager {

        if( $header === NULL ) return $this;

        // Well-formed Header Field. Don't need to be known

        if( $header instanceof Field ) {

            $this -> headers -> add( $header );

            return $this;
        }

        // Recursion...

        if( (array) $header === $header ) {

            foreach( $header as $n => $v ) {

                /**
                 * @internal
                 *
                 * Usually non-associative arrays have Headers in their values
                 */
                if( $v instanceof Field ) {

                    $this -> addHeader( $v, $n );

                } else {

                    $this -> addHeader( $n, $v );
                }
            }

            return $this;
        }

        // Checking if its a known Header Field

        if( array_key_exists( $header, $this -> known ) ) {

            /**
             * @internal
             *
             * Building full Classname, instantiating and adding Header to Headers' Collection
             */
            $class = sprintf( '%s\%s', self::FIELDS_NS, $this -> known[ $header ] );

            if( ! class_exists( $class ) ) return $this;

            $this -> headers -> add( new $class( [ 'value' => $value ] ) );

            return $this;
        }

        /**
         * @internal
         * If it is a unknown header, let's add it as Generic Header
         *
         * Generic Header don't need to be accepted
         */
        $this -> headers -> add(
            new Generic( [ 'value' => sprintf( '%s: %s', $header, $value ) ] )
        );

        return $this;
    }

    /**
     * Check if given Header Field exists
     *
     * This is an accessory to be used mainly in Mixin Objects
     *
     * @param string $header
     *  Header Field to be searched
     *
     * @return boolean
     *  TRUE if it exists and FALSE otherwise
     */
    public function hasHeader( $header ) : bool {
        return ( $this -> headers -> item( $header ) instanceof Field );
    }

    /**
     * Get registered Headers
     *
     * @param boolean $asString
     *  If TRUE, instead of the Lists Collection where the Headers have been
     *  stored,  a string representation of all Headers will be returned instead
     *
     * @return \Next\Components\Collections\Lists|string
     *  If `$asString` is set to FALSE, the Lists Collection with all added
     *  Headers will be returned
     *
     *  Otherwise a string representation of all Headers will be returned
     */
    public function getHeaders( $asString = FALSE ) {

        if( $asString === FALSE ) return $this -> headers -> getCollection();

        $header = NULL;

        $iterator = $this -> headers -> getIterator();

        iterator_apply(

            $iterator,

            function( \Iterator $iterator ) use( &$header ) : bool {

                $current = $iterator -> current();

                // Generic and Raw Headers are built "as is"

                if( $current instanceof Generic || $current instanceof Raw ) {

                    $header .= sprintf( "%s\r\n", $current -> getValue() );

                } else {

                    $header .= sprintf( "%s: %s\r\n", $current -> getName(), $current -> getValue() );
                }

                return TRUE;
            },

            [ $iterator ]
        );

        return rtrim( $header, "\r\n" );
    }
}
