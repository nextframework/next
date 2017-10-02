<?php

/**
 * Length Exception Class | Exception/Exceptions\LengthException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Exceptions;

require_once __DIR__ . '/LogicException.php';

/**
 * The LengthException defines an Exception Type for when
 * when a data length is invalid
 *
 * As for Next Framework, at least for now, we extend this definition
 * to a context instead of taking into account the literal meaning of
 * a length
 *
 * For example: Our Manager Table Manager needs data to insert/delete
 * records, but if the provided data-source doesn't have any, it has
 * then an invalid length for the context of populated data-source
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exceptions\LogicException
 */
class LengthException extends LogicException {}