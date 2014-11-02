<?php

namespace Next\Cache\Backend;

/**
 * APC Cache Backend Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class APC extends AbstractBackend {

    // Backend Interface Methods Implementation

    /**
     * Load Cached Data
     *
     * @param string $key
     *  Cache Key
     *
     * @param boolean|optional $keepSerialized
     *  Flag to condition if Cache Data will keep serialized or not
     *
     * @return mixed|boolean
     *
     *  FALSE if:
     *
     *   <ul>
     *
     *       <li>There is no Cached entry for given key</li>
     *
     *       <li>We were unable to read Cached Data</li>
     *
     *       <li>
     *
     *           Cache Integrity Validation is enabled and read
     *           Cached Data has a different hash
     *
     *       </li>
     *
     *   </ul>
     *
     *   <p>
     *       Otherwise, if <strong>$keepSerialized</strong> is set as TRUE
     *       the Cached Data will be returned as string, just like it
     *       was stored.
     *   </p>
     *
     *   <p>
     *       If this argument is set as FALSE, we'll unserialize it to
     *       the original state
     *   </p>
     */
    public function load( $key, $keepSerialized = TRUE ) {

        if( ! $this -> test( $key ) ) {
            return FALSE;
        }

        $data = apc_fetch( $key );

        if( $data === FALSE ) {
            return NULL;
        }

        // Should we check if Cache's Hash is valid?

        if( $this -> options -> security -> testValidity ) {

            $meta = $this -> meta -> load( $key );

            if( $meta !== FALSE && $meta['hash'] !== FALSE ) {

                // Comparing the Hashes

                if( $this -> hash( $data, $meta['cacheControl'] ) !== $meta['hash'] ) {

                    // We have a Invalid Cache, should we delete it?

                    if( $this -> options -> removeCorrupted !== FALSE ) {

                        $this -> remove( $key );

                        return FALSE;
                    }
                }
            }
        }

        return ( (bool) $keepSerialized !== FALSE ? $data : unserialize( $data ) );
    }

    /**
     * Add new Data into Cache
     *
     * @param string $key
     *  Cache Key
     *
     * @param mixed $value
     *  Data to Cache
     *
     * @param integer|optional $ttl
     *  Optional Lifetime
     *
     * @param boolean|optional $isTouching
     *  Flag to condition when we're touching the Cache File in order to give
     *  it an extra lifetime
     *
     * @return boolean
     *  TRUE on success and FALSE otherwise
     */
    public function add( $key, $value, $ttl = NULL, $isTouching = FALSE ) {

        // Serializing the Content

        $value = serialize( $value );

        // Building and Writing Meta Data

        $meta = $this -> metadata( $key, $value, (int) $ttl, $isTouching );

        $this -> meta -> write( $key, $meta );

        /**
         * @internal
         *
         * Storing APC Cache Data
         * Note that APC Cache Lifetime(s) are defined by its Meta Data
         */
        return apc_store( $key, $value, $meta['lifeTime'] );
    }

    /**
     * Remove Data from Cache
     *
     * @param string $key
     *  Cache Key
     *
     * @return boolean
     *  TRUE if Cache Metadata entry and the Cache itself were successfully removed
     */
    public function remove( $key ) {
        return ( $this -> meta -> delete( $key ) && apc_delete( $key ) );
    }

    /**
     * Test Cache Validity
     *
     * @param string $key
     *  Cache Key
     *
     * @return boolean
     *  TRUE if a Cache entry was found with given key AND it's a non-expired Cached Data
     */
    public function test( $key ) {

        $meta = $this -> meta -> load( $key );

        if( ( apc_exists( $key ) && ( (bool) $meta !== FALSE ) ) ) {

            // Checking if chosen Cache still alive

            if( $meta['expire'] <= time() ) {

                // If it doesn't, we'll remove it

                $this -> remove( $key );

                return FALSE;
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Clean Cached Data
     *
     * @param string|optional $mode
     *  Cleaning Mode
     *
     * @return boolean
     *  TRUE on success and FALSE otherwise
     *
     * @throws Next\Cache\Backend\BackendException
     *  Trying to clean old caches, which is not supported by APC Backend
     */
    public function clean( $mode = self::CLEAN_USER ) {

        switch( $mode ) {

            case self::CLEAN_ALL:
                return apc_clear_cache();
            break;

            case self::CLEAN_OLD:

                throw BackendException::cleanOldCache();

            break;

            case self::CLEAN_USER:
            default:
                return apc_clear_cache( 'user' );
            break;
        }
    }

    // Abstract Method Implementation

    /**
     * Check Backend Requirements
     *
     * @throws Next\Cache\Backend\BackendException
     *  APC Extension is not loaded
     */
    protected function checkRequirements() {

        if( ! extension_loaded( 'apc' ) ) {

            throw BackendException::unfullfilledRequirements(

                'APC Extension must be loaded before using its Backend!'
            );
        }
    }
}