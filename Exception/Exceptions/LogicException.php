<?php

/**
 * Logic Exception Class | Exception/Exceptions\LogicException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Exceptions;

require_once __DIR__ . '/../Exception.php';

use Next\Exception\Exception;    # Exception Class

/**
 * The LogicException defines an Exception Type for when a program
 * logic error occurs
 *
 * As for Next Framework, differently of the native \LogicException
 * class, a LogicException *may* also represent any other sort of
 * logical error IF none of the other Exception classes fits for
 * the purpose
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exception
 */
class LogicException extends Exception {}