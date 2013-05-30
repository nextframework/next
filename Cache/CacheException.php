<?php

namespace Next\Cache;

/**
 * Cache Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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
    const NO_METADATA               = 0x00000099;

    /**
     * Corrupted Metadata Structure
     *
     * @var integer
     */
    const CORRUPTED_METADATA        = 0x0000009A;

    // Exception Messages

    /**
     * Metadata Reading Impossibility
     *
     * Metadata Files could not be read by designed Cache Backend
     *
     * @return Next\Cache\CacheException
     *   Exception for Metadata readbility failure
     */
    public static function noMetadata() {

        return new self(

            'Unable to read Meta Data from Meta File',

            self::NO_METADATA
        );
    }

    /**
     * Corrupoted Metadata Structure
     *
     * Metadata File's Content is corrupted because it doesn't
     * follows the expected structure
     *
     * @return Next\Cache\CacheException
     *   Exception for Metadata integrity corruption
     */
    public static function corruptedMetadata() {

        return new self(

            'Corrupted Meta Data Structure',

            self::CORRUPTED_METADATA
        );
    }
}