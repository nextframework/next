<?php

namespace Next\Cache\Backend;

/**
 * Cache Backend Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class BackendException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000066, 0x00000098 );

    /**
     * Invalid Hash Type
     *
     * @var integer
     */
    const INVALID_HASH_TYPE = 0x00000066;

    /**
     * Cleaning Old Caches Impossibility
     *
     * @var integer
     */
    const CLEAN_OLD = 0x00000067;

    /**
     * Cleaning User Caches Impossibility
     *
     * @var integer
     */
    const CLEAN_USER = 0x00000068;

    // Exception Messages

    /**
     * Invalid Hash Type
     *
     * Chosen comparison Hash Type is invalid or not supported yet
     *
     * @return Next\Cache\Backend\BackendException
     *   Exception for Invalid Hash Type, used for data integrity check
     */
    public static function invalidHashType() {

        return new self(

            'Invalid or unsupported Hash Type',

            self::INVALID_HASH_TYPE
        );
    }

    /**
     * Clean Old Caches Impossibility
     *
     * Something is going wrong because chosen Cache Backend does not supports
     * the deletion of Old Cached Files
     *
     * @return Next\Cache\Backend\BackendException
     *   Exception for old caches cleaning from unsupported Backend context
     */
    public static function cleanOldCache() {

        return new self(

            'This Cache Backend doesn\'t supports removal of User Cache Data, yet',

            self::CLEAN_OLD
        );
    }

    /**
     * Clean User Caches Impossibility
     *
     * Something is going wrong because chosen Cache Backend does not supports
     * the deletion of User Cached Files
     *
     * @return Next\Cache\Backend\BackendException
     *   Exception for user caches cleaning from unsupported Backend context
     */
    public static function cleanUserCache() {

        return new self(

            'This Cache Backend doesn\'t supports removal of User Cache Data',

            self::CLEAN_USER
        );
    }
}