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

use Next\HTTP\Stream\Reader\ReaderException;      # HTTP Stream Reader Exception Class
use Next\HTTP\Stream\Adapter\Adapter;             # HTTP Stream Adapter Interface
use Next\Components\Object;                       # Object Class

/**
 * File Reader Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Reader extends Object implements Reader\Reader {

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
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for the HTTP Stream Reader
     */
    public function __construct( Adapter $adapter, $options = NULL ) {

        parent::__construct( $options );

        $adapter -> open();

        $this -> adapter = $adapter;
    }

    /**
     * Read some bytes from File Stream
     *
     * @param integer|optional $length
     *  Optional number of bytes to rwad
     *
     * @return string
     *  Always return the read data, because if failed
     *  to read an Exception is thrown
     *
     * @throws \Next\HTTP\Stream\Reader\ReaderException
     *  Number of bytes is equal zero
     *
     * @throws \Next\HTTP\Stream\Reader\ReaderException
     *  Fail when trying to read
     */
    public function read( $length = 4096 ) {

        if( $length == 0 ) {

            throw ReaderException::logic(

                'You have to define how many bytes will be read from Stream'
            );
        }

        // Reading Stream

        $read = fread( $this -> adapter -> getStream(), $length );

        if( $read === FALSE ) {

            throw ReaderException::readFailure();
        }

        return $read;
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
     * @throws \Next\HTTP\Stream\Reader\ReaderException
     *  Number of bytes is zero
     *
     * @throws \Next\HTTP\Stream\Reader\ReaderException
     *  Fail when trying to read
     */
    public function readLine( $length = 1024 ) {

        if( $length == 0 ) {

            throw ReaderException::logic(

                'You have to define how many bytes will be read from Stream'
            );
        }

        return fgets( $this -> adapter -> getStream(), $length );
    }

    /**
     * Read the whole Stream
     *
     * @return string
     *  Read data
     */
    public function readAll() {

        $output = '';

        /**
         * @internal
         * Using do...while() instead of "normal" while in order to avoid
         * a infinite loop.
         *
         * Tip from "Jet" (http://br.php.net/manual/en/function.feof.php#77912)
         */
        do {

            $output .= $this -> read();

        } while( ! $this -> adapter -> eof() );

        return $output;
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