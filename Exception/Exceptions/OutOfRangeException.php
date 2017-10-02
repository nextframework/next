<?php

/**
 * Out of Range Exception Class | Exception/Exceptions\OutOfRangeException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Exceptions;

require_once __DIR__ . '/LogicException.php';

/**
 * The OutOfRangeException defines an Exception Type for when
 * a given offset is out of the range of a data-set
 *
 * For example: A Lists Collection with 10 items and the 11st is
 * being requested
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exceptions\LogicException
 */
class OutOfRangeException extends LogicException {}