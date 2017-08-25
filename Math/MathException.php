<?php

namespace Next\Math;

class MathException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     *
     * @todo Remove or change for different values
     */
    protected $range = array( 0x00000033, 0x00000065 );
}