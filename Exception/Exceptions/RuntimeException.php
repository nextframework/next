<?php

/**
 * Runtime Exception Class | Exception/Exceptions\RuntimeException.php
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
 * The RuntimeException defines an Exception Type for when
 * something unexpected happens only in runtime, something that requires
 * external interference (i.e. server environment) to occur
 *
 * However, obviously, this should be used only if the situation doesn't
 * fit the purpose of any other Exception Classes first
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exception
 */
class RuntimeException extends Exception {}