<?php

/**
 * Caching Exception Class | Cache\CacheException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Cache;

use Next\Components\Object;    # Object Class

/**
 * Defines wrapper static methods for all Exceptions thrown
 * within the Cache Module
 *
 * @package    Next\Cache
 */
class CacheException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000099, 0x000000CB );

    /**
     * Metadata reading impossibility
     *
     * @var integer
     */
    const INVALID_CACHING_SCHEMA    = 0x00000099;

    // Exception Messages

    /**
     * Invalid Caching Schema
     *
     * Given Object is not a valid Caching Schema because it doesn't
     * implements \Next\Cache\Schema\Schema
     *
     * @param \Next\Components\Object $object
     *  Object assigned as Caching Schema
     *
     * @return \Next\Cache\CacheException
     *  Exception for invalid Caching Schema
     */
    public static function invalidCachingSchema( Object $object ) {

        return new self(

            '<strong>%s</strong> is not a valid Caching Schema.

            <br /><br />

            Caching Schema must implement Caching Schema Interface',

            self::INVALID_CACHING_SCHEMA,

            (string) $object
        );
    }
}