<?php

/**
 * Math Exception Class | Math\MathException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Math;

/**
 * Defines wrapper static methods for all Exceptions thrown
 * within the Math Module
 *
 * @package    Next\Math
 *
 * @uses       \Next\Components\Debug\Exception
 */
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