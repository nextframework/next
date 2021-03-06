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
 * through which all errors that must be sent to the UI by a Controller
 * is automatically done by the Dispatcher requiring the developer only to
 * pretty much only echo a Template Variable
 *
 * @package    Next\Controller
 *
 * @uses       Next\Exception\Exception
 */
class ControllerException extends Exception {}