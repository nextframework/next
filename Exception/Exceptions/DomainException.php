<?php

/**
 * Domain Exception Class | Exception/Exceptions\DomainException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Exceptions;

require_once __DIR__ . '/LogicException.php';

/**
 * The DomainException defines an Exception Type for when
 * a value is invalid within predefined set of data. E.g:
 *
 * 'Foo' is not a valid abbreviation of a Month accordingly to the
 * International System of Units
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exceptions\LogicException
 */
class DomainException extends LogicException {}