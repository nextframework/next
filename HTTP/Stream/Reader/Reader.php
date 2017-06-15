<?php

/**
 * HTTP Stream Reader Interface | HTTP\Stream\Reader\Reader.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
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
     *  Optional number of bytes to read
     */
    public function read( $length = NULL );

    /**
     * Read one line from File Stream
     *
     * @param integer|optional $length
     *  Optional number of bytes to read from line
     */
    public function readLine( $length = 1024 );

    /**
     * Read the whole File Stream
     */
    public function readAll();
}
