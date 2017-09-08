<?php

/**
 * Components Debug Error & Exception Handler Interface | Debug\Exception\Handler.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Debug\Exception;

/**
 * Describes an Error/Exception Registrable Handler
 *
 * @package    Next\Debug
 */
interface Handler {

    /**
     * Registers the Exception/Error Handler
     */
    public static function register();
}