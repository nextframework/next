<?php

/**
 * HTTP Stream Writer Class | HTTP\Stream\Writer.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\LengthException;
use Next\Exception\Exceptions\RuntimeException;

use Next\Components\Object;              # Object Class
use Next\Components\Invoker;             # Invoker Class
use Next\HTTP\Stream\Adapter\Adapter;    # HTTP Stream Adapter Interface

/**
 * HTTP Stream Writer writes data to an opened Stream
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Exception\Exceptions\LengthException;
 *             Next\Exception\Exceptions\RuntimeException
 *             Next\Components\Object
 *             Next\Components\Invoker
 *             Next\HTTP\Stream\Adapter\Adapter
 */
class Writer extends Object {

    /**
     * Stream Adapter
     *
     * @var Adapter $adapter
     */
    private $adapter;

    /**
     * Stream Writer Constructor
     *
     * @param \Next\HTTP\Stream\Adapter\Adapter $adapter
     *  Stream Adapter where data will be written
     */
    public function __construct( Adapter $adapter ) {

        parent::__construct();

        $adapter -> open();

        $this -> adapter = $adapter;

        /**
         * @internal
         *
         * Extending HTTP Stream Writer's Context to the associated
         * HTTP Stream Adapter ONLY to allow the HTTP Stream can be closed
         * from Writer Context
         */
        $this -> extend(
            new Invoker( $this, $this -> adapter, [ 'close' ] )
        );
    }

    /**
     * Write bytes to an opened Stream
     *
     * @param string $string
     *  Data to write
     *
     * @param integer|optional $length
     *  Optional number of bytes of given data to write
     *
     * @return integer
     *  Always return the number of bytes written, because if failed
     *  to write an Exception is thrown
     *
     * @throws \Next\Exception\Exceptions\LengthException
     *  Thrown when the number of bytes to write is zero
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown when something went wrong when trying to write data
     */
    public function write( $string, $length = NULL ) {

        if( $length !== NULL && (int) $length == 0 ) {

            throw new LengthException(
                'Using zero-length when writing data, will result in a
                blank file/empty stream'
            );
        }

        $written = fwrite(

            $this -> adapter -> getStream(), $string,

            ( $length !== NULL ? $length : strlen( $string ) )
        );

        if( $written === FALSE ) {
            throw new RuntimeException( 'Unable to write data to opened Stream' );
        }

        return $written;
    }

    // Interface Methods Implementation

    /**
     * Get Adapter Object
     *
     * @return \Next\HTTP\Stream\Adapter\Adapter
     *  HTTP Stream Adapter
     */
    public function getAdapter() {
        return $this -> adapter;
    }

    /**
     * Get Stream Resource
     *
     * @return resource
     *  HTTP Stream
     */
    public function getStream() {
        return $this -> adapter -> getStream();
    }
}