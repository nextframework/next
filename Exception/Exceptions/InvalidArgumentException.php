<?php

/**
 * Invalid Argument Exception Class | Exception/Exceptions\InvalidArgumentException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Exceptions;

require_once __DIR__ . '/LogicException.php';

/**
 * The InvalidArgumentException defines an Exception Type for when
 * an argument is not of the expected type
 *
 * As for Next Framework, at least for now, we also consider missing
 * values, like Parameter Options, as InvalidArgumentException as well
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exceptions\LogicException
 */
class InvalidArgumentException extends LogicException {}