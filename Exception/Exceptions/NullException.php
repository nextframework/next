<?php

/**
 * Null Exception Class | Exception/Exceptions\NullException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Exceptions;

require_once __DIR__ . '/RuntimeException.php';

/**
 * The NullException defines an Exception Type for "errors" that will
 * always be caught and NEVER re-thrown
 *
 * For example: As part of our Extended Context Concept, if an
 * undefined method is trying to be called — which will be handled by
 * Next\Components\Object::__call() — we needed a way to know that
 * none of methods of all Objects added to the Extended Context could
 * not be found so we could try to find as a Prototyped Resource instead.
 *
 * This "hint" was the NullException as it is caught and never re-thrown.
 * If whatever was called also can't be found as one of the
 * Prototyped Resources THEN a legitimate Exception is thrown
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exceptions\RuntimeException
 */
class NullException extends RuntimeException {}