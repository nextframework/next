<?php

/**
 * Loader Exception File | Loader\LoaderException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Loader;

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
