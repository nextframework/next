<?php

/**
 * HTTP Stream Reader Exception Class | HTTP\Stream\Reader\ReaderException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream\Reader;

/**
 * Stream Reader Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ReaderException extends \Next\Components\Debug\Exception {

    /**
     * Unable to Read Data
     *
     * @var integer
     */
    const UNABLE_TO_READ    = 0x00000561;

    // Exception Messages

    /**
     * Unable to read bytes from opened Stream
     *
     * @return \Next\HTTP\Stream\Reader\ReaderException
     *  Exception for Stream readability failure
     */
    public static function readFailure() {

        return new self(

            'Fail when trying to read from Stream',

            self::UNABLE_TO_READ
        );
    }
}
