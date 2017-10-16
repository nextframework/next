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

use Next\HTTP\Stream\Writer\WriterException;      # HTTP Stream Writer Exception Class
use Next\HTTP\Stream\Adapter\Adapter;             # HTTP Stream Adapter Interface
use Next\Components\Object;                       # Object Class

/**
 * File Writer Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Writer extends Object implements Writer\Writer {

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
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for the HTTP Stream Writer
     */
    public function __construct( Adapter $adapter, $options = NULL ) {

        parent::__construct( $options );

        $adapter -> open();

        $this -> adapter = $adapter;
    }

    /**
     * Write some bytes in File Stream
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
     * @throws \Next\HTTP\Stream\Writer\WriterException
     *  Number of bytes is zero
     *
     * @throws \Next\HTTP\Stream\Writer\WriterException
     *  Fail when trying to write data
     */
    public function write( $string, $length = NULL ) {

        // Writing with length...

        if( $length !== NULL ) {

            $length = (int) $length;

            if( $length == 0 ) {

                throw WriterException::logic(

                    'Using zero-length when writing data, will result in a blank file/empty stream'
                );
            }

            $write = fwrite( $this -> adapter -> getStream(), $string, $length );

        } else {

            // ... and without it

            $write = fwrite( $this -> adapter -> getStream(), $string );
        }

        if( $write === FALSE ) {

            throw WriterException::writeFailure();
        }

        return $write;
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