<?php

/**
 * HTTP Stream Writer Exception Class | HTTP\Stream\Writer\WriterException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream\Writer;

/**
 * Stream Writer Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class WriterException extends \Next\Exception\Exception {

    /**
     * Unable to Write Data
     *
     * @var integer
     */
    const UNABLE_TO_WRITE    = 0x00000595;

    // Exception Messages

    /**
     * Unable to write bytes in opened Stream
     *
     * @return \Next\HTTP\Stream\Writer\WriterException
     *  Exception for Stream writability failure
     */
    public static function writeFailure() {

        return new self(

            'Fail when trying to write data on Stream',

            self::UNABLE_TO_WRITE
        );
    }
}