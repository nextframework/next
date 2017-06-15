<?php

/**
 * Loader Exception File | Loader\LoaderException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Loader;

require_once '/../Exception.php';

/**
 * Loader Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class LoaderException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x0000062D, 0x0000065F );

    /**
     * Duplicated AutoLoader
     *
     * @var integer
     */
    const DUPLICATED    = 0x0000062D;

    /**
     *  Unknown AutoLoader
     *
     * @var integer
     */
    const UNKNOWN       = 0x0000062E;

    // Exception Messages

    /**
     * Duplicated AutoLoader
     *
     * @return \Next\LoaderException
     *  Exception for duplicated Autoloader
     */
    public static function duplicated() {

        return new self(

            'This AutoLoader already was registered!',

            self::DUPLICATED
        );
    }

    /**
     * Unknown AutoLoader
     *
     * @return \Next\LoaderException
     *  Exception for unknown AutoLoader
     */
    public static function unknown() {

        return new self(

            'This AutoLoader was not registered yet!',

            self::UNKNOWN
        );
    }
}
