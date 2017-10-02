<?php

/**
 * Access Violation Exception Class | Exception/Exceptions\AccessViolationException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Exceptions;

require_once __DIR__ . '/RuntimeException.php';

/**
 * The AccessViolationException defines an Exception Type for when
 * data of any sort is being accessed when the can't or shouldn't being
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exceptions\RuntimeException
 */
class AccessViolationException extends RuntimeException {}