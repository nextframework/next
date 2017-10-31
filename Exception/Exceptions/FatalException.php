<?php

/**
 * Fatal Exception Class | Exception/Exceptions\FatalException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Exceptions;

require_once __DIR__ . '/RuntimeException.php';

/**
 * The FatalException defines an Exception Type for when
 * something fatal, that would interrupt the normal flow of the
 * Request occurs
 *
 * As for Next Framework, for example, a FatalException occurs when
 * trying to initialize Sessions when Headers have already been sent
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exceptions\RuntimeException
 */
class FatalException extends RuntimeException {}