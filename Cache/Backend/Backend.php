<?php

namespace Next\Cache\Backend;

/**
 * Cache Backend Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Backend {

    // Cleaning Modes

    /**
     * Clean Everything
     *
     * @var integer
     */
    const CLEAN_ALL     = 1;

    /**
     * Clean Old Cache only
     *
     * @var integer
     */
    const CLEAN_OLD     = 2;

    /**
     * Clean User cache only. APC exclusivity
     *
     * @var integer
     */
    const CLEAN_USER    = 3;

    /**
     * Load Cached Data
     *
     * @param string $key
     *   Cache Key
     *
     * @param boolean|optional $keepSerialized
     *   Flag to condition if Cache Data will keep serialized or not
     */
    public function load( $key, $keepSerialized = TRUE );

    /**
     * Add new Data into Cache
     *
     * @param string $key
     *   Cache Key
     *
     * @param mixed $data
     *   Data to Cache
     *
     * @param integer|optional $ttl
     *   Optional Lifetime
     *
     * @param boolean|optional $isTouching
     *   Flag to condition when we're touching the Cache File in order to give it
     *   an extra lifetime
     */
    public function add( $key, $data, $ttl = NULL, $isTouching = FALSE );

    /**
     * Remove Data from Cache
     *
     * @param string $key
     *   Cache Key
     */
    public function remove( $key );

    /**
     * Test Cache Validity
     *
     * @param string $key
     *   Cache Key
     */
    public function test( $key );

    /**
     * Add more lifetime to Cached Data
     *
     * @param string $key
     *   Cache Key
     *
     * @param integer $extra
     *   Extra Lifetime
     */
    public function touch( $key, $extra );

    /**
     * Clean Cached Data
     *
     * @param integer|optional $mode
     *   Cleaning Mode
     */
    public function clean( $mode = self::CLEAN_ALL );
}
