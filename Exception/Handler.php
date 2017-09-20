<?php

/**
 * Components Debug Error & Exception Handler Interface | Exception\Handler.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception;

/**
 * Describes an Error/Exception Registrable Handler
 *
 * @package    Next\Exception
 */
interface Handler {

    /**
     * Registers the Exception/Error Handler
     */
    public static function register();
}