<?php

namespace Next\HTTP\Headers;

/**
 * Headers Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class HeadersException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000462, 0x00000494 );

    /**
     * All Header Fields are invalid
     *
     * @var integer
     */
    const ALL_INVALID    = 0x00000462;
}
