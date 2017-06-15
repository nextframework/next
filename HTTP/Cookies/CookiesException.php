<?php

/**
 * HTTP Cookies Exception Class | HTTP\Cookies\CookiesException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
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
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x000003FC, 0x0000042E );

    /**
     * All Cookies are invalid
     *
     * @var integer
     */
    const ALL_INVALID    = 0x000003FC;
}