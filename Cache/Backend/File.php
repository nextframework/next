<?php

namespace Next\Cache\Backend;

/**
 * Next Cache Exception Class
 */
use Next\Cache\CacheException;
use Next\HTTP\Stream\Reader\ReaderException;
use Next\HTTP\Stream\Writer\WriterException;

/**
 * Next File Tools
 */
use Next\File\Tools;

/**
 * Next File Socket Adapter Class
 */
use Next\HTTP\Stream\Adapter\Socket;

/**
 * Next File Reader Class
 */
use Next\HTTP\Stream\Reader;

/**
 * Next File Writer Class
 */
use Next\HTTP\Stream\Writer;

/**
 * Cache File Backend Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class File extends AbstractBackend {

    // Backend Interface Methods Implementation

    /**
     * Load Cached Data
     *
     * @param string $key
     *   Cache Key
     *
     * @param boolean|optional $keepSerialized
     *   Flag to condition if Cache Data will keep serialized or not
     *
     * @return mixed|boolean
     *
     *   FALSE if:
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

        try {

            // Setting Up Reader Object

            $reader = new Reader( new Socket( $this -> buildFilePath( $key ), 'r' ) );

            // Reading Cached Content

            $data = $reader -> readAll();

            // Closing Stream

            $reader -> getStream() -> close();

            if( empty( $data ) ) {
                return NULL;
            }

            $meta = $this -> meta -> load( $key );

            if( $meta !== FALSE ) {

                // Should we check if Cache's Hash is valid?

                if( $this -> options -> testValidity && $meta['hash'] !== FALSE ) {

                    // Comparing the Hashes

                    if( $this -> hash( $data, $meta['cacheControl'] ) !== $meta['hash'] ) {

                        // We have a Invalid Cache, should we delete it?

                        if( $this -> options -> removeCorrupted !== FALSE ) {

                            try {

                                $this -> remove( $key );

                            } catch( CacheException $e ) {}
                        }

                        return FALSE;
                    }
                }

                // Should we Decompress the Cached Content?

                if( $meta['compressed'] ) {

                    if( function_exists( 'gzuncompress' ) ) {
                        $data = gzuncompress( $data );
                    }
                }

                return ( (bool) $keepSerialized !== FALSE ? $data : unserialize( $data ) );
            }

            return $data;

        } catch( ReaderException $e ) {

            /**
             * @internal
             *
             * As we are trying to read a file, if we got an Exception
             * means the file doesn't exists or it's not readable
             *
             * So the Cache Data must be regenerated
             */
            return FALSE;
        }
    }

    /**
     * Add new Data into Cache
     *
     * @param string $key
     *   Cache Key
     *
     * @param mixed $value
     *   Data to Cache
     *
     * @param integer|optional $ttl
     *   Optional Lifetime
     *
     * @param boolean|optional $isTouching
     *   Flag to condition when we're touching the Cache File in order to give
     *   it an extra lifetime
     *
     * @return boolean
     *   TRUE on success and FALSE otherwise
     */
    public function add( $key, $value, $ttl = NULL, $isTouching = FALSE ) {

        // Serializing the Content

        $value = serialize( $value );

        // Should we compress Data?

        if( $this -> options -> compression -> enabled ) {

            if( function_exists( 'gzcompress' ) ) {

                $value = gzcompress( $value, $this -> options -> compression -> level );
            }
        }

        try {

            // Setting Up Writer Object

            $writer = new Writer( new Socket( $this -> buildFilePath( $key ), 'w' ) );

            // Building and Writing Meta Data

            $this -> meta -> write( $key, $this -> metadata( $key, $value, (int) $ttl, $isTouching ) );

            // Writing Data

            $bytes = (bool) $writer -> write( $value );

            // Closing File Stream

            $writer -> getStream() -> close();

            return ( $bytes > 0 );

        } catch( WriterException $e ) {

            /**
             * As we are trying to create a Cache File, if we got an Exception
             * means the file exists but it's not writable or it doesn't exists
             * and its parent diectory is not writable
             *
             * So the Cache Data could not be created and, when trying to load
             * it, the cache will be regenerated anyway
             */

            return FALSE;
        }
    }

    /**
     * Remove Data from Cache
     *
     * @param string $key
     *   Cache Key
     *
     * @return boolean
     *   TRUE if Cache Metadata entry and the Cache itself were successfully removed
     */
    public function remove( $key ) {
        return ( ! Tools::delete( $this -> buildFilePath( $key ) ) && $this -> meta -> delete( $key ) );
    }

    /**
     * Test Cache Validity
     *
     * @param string $key
     *   Cache Key
     *
     * @return boolean
     *   TRUE if a Cache entry was found with given key AND it's a non-expired Cached Data
     */
    public function test( $key ) {

        clearstatcache();

        $meta = $this -> meta -> load( $key );

        if( ( file_exists( $this -> buildFilePath( $key ) ) && ( (bool) $meta !== FALSE ) ) ) {

            // Checking if chosen Cache still alive

            if( $meta['expire'] <= time() ) {

                // if it doesn't, we'll try to remove it

                try {

                    $this -> remove( $key );

                } catch( CacheException $e ) {}

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
     *   Cleaning Mode
     *
     * @return boolean
     *   TRUE on success and FALSE otherwise
     *
     * @throws Next\Cache\Backend\BackendException
     *   Trying to clean old or user caches, which is not supported
     *   by File Backend
     */
    public function clean( $mode = self::CLEAN_USER ) {

        clearstatcache();

        switch( $mode ) {

            case parent::CLEAN_OLD:

                throw BackendException::cleanOldCache();

            break;

            case parent::CLEAN_USER:

                throw BackendException::cleanUserCache();

            break;

            case parent::CLEAN_ALL:
            default:

                return Tools::delete( $this -> options -> outputDirectory );

            break;
        }
    }

    // Parameterizable Interface Method Implementation

    /**
     * Set Up Backend Options
     *
     * @return array
     *   File Backend specific default options
     */
    public function setOptions() {

        return array(

            'compression'       => array( 'enabled' => TRUE, 'level'   => 9 ),

            'filePrefix'        => 'Next.Cache.File',

            'outputDirectory'   => 'Cache'
        );
    }

    // Abstract Methods Implementation

    /**
     * Check Backend Requirements
     *
     * For File Backend... Nothing! All the needs is in Next\Cache\Backend\File::checkIntegrity()
     */
    protected function checkRequirements() {}

    // Method Overwriting

    /**
     * Check Options Integrity
     *
     * @throws Next\Cache\Backend\BackendException
     *  Output Cache Directory is empty
     *
     * @throws Next\Cache\Backend\BackendException
     *   Output Cache Directory doesn't exists
     *
     * @throws Next\Cache\Backend\BackendException
     *   Output Cache Directory is not writeable
     */
    protected function checkIntegrity() {

        // Inherit Integrity Checks from Parent Class

        parent::checkIntegrity();

        // Shortening...

        $dir =& $this -> options -> outputDirectory;

        // Checking if Output Directory is set

        if( empty( $dir ) ) {

            throw BackendException::unfullfilledRequirements(

                'Output Cache Directory must be set as non empty string'
            );
        }

        $info = new \SplFileInfo( $dir );

        // Checking if Output Directory exists...

        if( ! $info -> isDir() ) {

            throw BackendException::unfullfilledRequirements(

                'Output Cache Directory <strong>%s</strong> doesn\'t exists',

                array( $dir )
            );
        }

        // ... and if is writeable

        if( ! $info -> isWritable() ) {

            throw BackendException::unfullfilledRequirements(

                'Output Cache Directory <strong>%s</strong> is not writable',

                array( $dir )
            );
        }
    }

    // Auxiliary Methods

    /**
     * Build the Filepath
     *
     * @param string $key
     *   Cache Key
     *
     * @return string
     *   The full path of given Cache Key, including Output Directory and File prefix, if any
     */
    private function buildFilePath( $key ) {

        return sprintf(

            '%s/%s.cache',

            $this -> options -> outputDirectory,

            $this -> generateID( $key, $this -> options -> filePrefix )
        );
    }
}