<?php

namespace Next\HTTP\Stream\Writer;

use Next\HTTP\Stream\Stream;    # Stream Interface

/**
 * Stream Writer Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Writer extends Stream {

    /**
     * Write some bytes in File Stream
     *
     * @param string $string
     *  Data to write
     *
     * @param integer|optional $length
     *  Optional number of bytes of given data to write
     */
    public function write( $string, $length = NULL );
}
