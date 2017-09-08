<?php

/**
 * HTTP Headers Exception Class | HTTP\Headers\HeadersException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers;

/**
 * Headers Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class HeadersException extends \Next\Components\Debug\Exception {

    /**
     * All Header Fields are invalid
     *
     * @var integer
     */
    const ALL_INVALID    = 0x00000462;
}
