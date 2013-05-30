<?php

namespace Next\HTTP\Stream\Reader;

use Next\HTTP\Stream\Stream;    # Stream Interface

/**
 * Stream Reader Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Reader extends Stream {

    /**
     * Read some bytes from File Stream
     *
     * @param integer|optional $length
     *   Optional number of bytes to read
     */
    public function read( $length = NULL );

    /**
     * Read one line from File Stream
     *
     * @param integer|optional $length
     *   Optional number of bytes to read from line
     */
    public function readLine( $length = 1024 );

    /**
     * Read the whole File Stream
     */
    public function readAll();
}
