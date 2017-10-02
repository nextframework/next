<?php

/**
 * Underflow Exception Class | Exception/Exceptions\UnderflowException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Exceptions;

require_once __DIR__ . '/RuntimeException.php';

/**
 * The UnderflowException defines an Exception Type for when an
 * invalid operation is occurring with a Collection when it shouldn't
 *
 * For example: Trying to remove an item from an Empty Collection
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exceptions\LogicException
 */
class UnderflowException extends RuntimeException {}