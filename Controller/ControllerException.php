<?php

/**
 * Controllers Exception Class | Controller\ControllerException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller;

use Next\Exception\Exception;    # Exception Class

/**
 * The ControllerException Class outlines the Error Standardization Concept
 * through which all errors that must be sent to UI from a
 * `\Next\Controller\Controller` is automatically done by the
 * `\Next\Controller\Dispatcher\Dispatcher` in charge, requiring the
 * developer only to echo a Template Variable
 *
 * @package    Next\Controller
 *
 * @uses       Next\Exception\Exception
 */
class ControllerException extends Exception {}