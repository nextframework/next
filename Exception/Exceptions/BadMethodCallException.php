<?php

/**
 * Bad Method Call Exception Class | Exception/Exceptions\BadMethodCallException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Exceptions;

require_once __DIR__ . '/LogicException.php';

/**
 * The BadMethodCallException defines an Exception Type for when
 * an undefined method is invoked or an expected argument of
 * it (usually dynamic — otherwise PHP would handle it) is missing
 *
 * As for Next Framework, though, it also represents issues risen when
 * invoking a method that doesn't fit any of the other available
 * Exception Classes but that's not too generic to be considered a
 * RuntimeException nor a LogicException
 *
 * For example, when a native Exception is thrown and we need to
 * re-thrown it as one of ours
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exceptions\LogicException
 */
class BadMethodCallException extends LogicException {}