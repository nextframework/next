<?php

/**
 * HTTP Stream Reader Class | HTTP\Stream\Reader.php
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

use Next\HTTP\Stream\Adapter\Adapter;    # HTTP Stream Adapter Interface
use Next\Components\Object;              # Object Class

/**
 * HTTP Stream Reader reads data from an opened Stream
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Exception\Exceptions\LengthException;
 *             Next\Exception\Exceptions\RuntimeException
 *             Next\HTTP\Stream\Adapter\Adapter
 *             Next\Components\Object
 */
class Reader extends Object {

    /**
     * Stream Adapter
     *
     * @var Adapter $adapter
     */
    private $adapter;

    /**
     * Stream Reader Constructor
     *
     * @param \Next\HTTP\Stream\Adapter\Adapter $adapter
     *  Stream Adapter from which data will be read
     */
    public function __construct( Adapter $adapter ) {

        parent::__construct();

        $adapter -> open();

        $this -> adapter = $adapter;
    }

    /**
     * Read some bytes from File Stream
     *
     * @param integer|optional $length
     *  Optional number of bytes to read
     *
     * @return string
     *  Always return the read data, because if failed
     *  to read an Exception is thrown
     *
     * @throws \Next\Exception\Exceptions\LengthException
     *  Thrown when the number of bytes to read is zero
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown when something went wrong when trying to read data
     */
    public function read( $length = 4096 ) : string {

        if( $length == 0 ) {

            throw new LengthException(
                'The amount of bytes to be read from opened Stream must be defined'
            );
        }

        // Reading Stream

        if( ( $data = fread( $this -> adapter -> getStream(), $length ) ) === FALSE ) {
            throw new RuntimeException( 'Unable to read data from opened Stream' );
        }

        return $data;
    }

    /**
     * Read one line from Stream
     *
     * @param integer|optional $length
     *  Optional number of bytes to read from line
     *
     * @return string Always return the read data, because if failed
     * to read an Exception is thrown
     *
     * @throws \Next\Exception\Exceptions\LengthException
     *  Thrown when the number of bytes to read is zero
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown when something went wrong when trying to read data
     */
    public function readLine( $length = 1024 ) : string {

        if( $length == 0 ) {

            throw new LengthException(
                'The amount of bytes to be read from opened Stream must be defined'
            );
        }

        // Reading Stream

        if( ( $data = fgets( $this -> adapter -> getStream(), $length ) ) === FALSE ) {
            throw new RuntimeException( 'Unable to read data from opened Stream' );
        }

        return $data;
    }

    /**
     * Read the whole Stream
     *
     * @return string
     *  Read data
     */
    public function readAll() : string {

        $data = '';

        /**
         * @internal
         *
         * Using do...while() instead of "normal" while in order to avoid
         * an infinite loop.
         *
         * Tip from "Jet" (http://php.net/manual/en/function.feof.php#77912)
         */
        do {

            $data .= $this -> read();

        } while( ! $this -> adapter -> eof() );

        return $data;
    }
}