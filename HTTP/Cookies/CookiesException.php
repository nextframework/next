<?php

/**
 * HTTP Cookies Exception Class | HTTP\Cookies\CookiesException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Cookies;

/**
 * Cookies Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class CookiesException extends \Next\Components\Debug\Exception {

    /**
     * All Cookies are invalid
     *
     * @var integer
     */
    const ALL_INVALID    = 0x000003FC;
}