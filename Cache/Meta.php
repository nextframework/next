<?php

namespace Next\Cache;

use Next\HTTP\Stream\Reader\ReaderException;    # Stream Reader Exception Class
use Next\HTTP\Stream\Writer\WriterException;    # Stream Writer Exception Class
use Next\Cache\CacheException;                  # Cache Exception Class
use Next\Cache\Backend\Backend;                 # Cache Backend Interface
use Next\HTTP\Stream\Adapter\Socket;            # Stream Socket Adapter Class
use Next\HTTP\Stream\Reader;                    # Stream Reader Class
use Next\HTTP\Stream\Writer;                    # Stream Writer Class

/**
 * Cache Metadata Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Meta {

    /**
     * Backend Options
     *
     * @var Next\Components\Parameter $backendOptions
     */
    private $backendOptions;

    /**
     * Meta Cache Constructor
     *
     * @param Next\Cache\Backend\Backend $backend
     *   Backend from which read options
     */
    public function __construct( Backend $backend ) {

        $this -> backendOptions = $backend -> getOptions();
    }

    /**
     * Load Data from Meta File
     *
     * @param string|optional $key
     *   Optional Metadata Entry
     *
     * @return boolean|string|array|null
     *
     *   FALSE if unable to read Metadata File
     *
     *   If <strong>$key</strong> argument IS null the whole Metadata
     *   Content will be returned
     *
     *   If <strong>$key</strong> is NOT null and given key is present in
     *   Metadata Content, for simple Information, a string will returned.
     *   For complex Information, an array will.
     *
     *   If given key is not present in Metadata Content OR an Exception is thrown,
     *   NULL will returned
     *
     * @throws Next\Cache\CacheException
     *   Fail to read Meta Data
     *
     * @throws Next\Cache\CacheException
     *   Meta Data is Corrupted (not an array)
     */
    public function load( $key = NULL ) {

        try {

            // Setting Up Reader Object

            $reader = new Reader(

                new Socket(

                    sprintf(

                        '%s/%s',

                        $this -> backendOptions -> meta -> path,
                        $this -> backendOptions -> meta -> file
                    ),

                    'r'
               )
            );

            // Reading Cached Content

            $data = $reader -> readAll();

            // Closing Stream

            $reader -> getStream() -> close();

            if( ! $data ) {
                throw CacheException::noMetadata();
            }

            // Decompressing Data

            if( function_exists( 'gzuncompress' ) ) {
                $data = gzuncompress( $data );
            }

            // Unserializing Data

            $data = unserialize( $data );

            if( ! is_array( $data ) ) {

                throw CacheException::corruptedMetadata();
            }

            // What should we return?

            if( ! is_null( $key ) ) {

                $key = md5( $key );

                return ( array_key_exists( $key, $data ) ? $data[ $key ] : NULL );

            } else {

                return $data;
            }

        } catch( ReaderException $e ) {

            /**
             * @internal
             * As we are trying to read a file, if we got an Exception
             * means the file doesn't exists or it's not readable
             *
             * So the Meta Data File must be regenerated
             */
            return FALSE;
        }
    }

    /**
     * Write Data in Meta File
     *
     * @param string $key
     *   Metadata Entry Key
     *
     * @param array $meta
     *   Metadata to write
     *
     * @return boolean
     *   TRUE if Metadata was successfully written and FALSE otherwise
     */
    public function write( $key, array $meta ) {

        // Load current Meta Data

        $data = $this -> loadMeta();

        // If we have an empty array OR there is no entry for given Cache key, we'll add it

        $key = md5( $key );

        if( count( $data ) == 0 || ! array_key_exists( $key, $data ) ) {

            $data[ $key ] = $meta;

        } else {

            // Otherwise we'll merge them

            $data[ $key ] = array_merge( $data[ $key ], $meta );
        }

        return $this -> writeMeta( $data );
    }

    /**
     * Delete Meta Data entry
     *
     * @param string $key
     *   Metadata Entry Key
     *
     * @return boolean
     *   TRUE if Metadata was successfully written and FALSE otherwise
     */
    public function delete( $key ) {

        // Load current Meta Data

        $data = $this -> loadMeta();

        $key = md5( $key );

        if( count( $data ) != 0 && array_key_exists( $key, $data ) ) {

            unset( $data[ $key ] );
        }

        // Updating Meta Data File

        return $this -> writeMeta( $data );
    }

    // Auxiliary Methods

    /**
     * Loading Metadata Wrapper
     *
     * Wrapper method for Meta Data File Contents loading.
     * If the loading process fail for some reason, an empty array will be returned
     *
     * @return array
     *   If we're able to load contents from existent Metadata File,
     *   then it will be returned. Otherwise, an empty array will
     */
    private function loadMeta() {

        // Trying to read current Meta Data contents

        try {

            // We are passing $key as Next\Cache\Meta::read() parameter, but it'll not be used

            $data = $this -> load();

        } catch( CacheException $e ) {

            /**
             * @internal
             * If we got an Exception here means something is wrong with
             * Meta Data File so, we have to create it
             */
            $data = array();
        }

        return ( $data !== FALSE ? $data : array() );
    }

    /**
     * Writing Metadata Wrapper
     *
     * @param array $data
     *   Data to Write
     *
     * @return boolean
     *   TRUE if Metadata was successfully written and FALSE otherwise
     */
    private function writeMeta( array $data ) {

        // Serializing the Content (required by compression below)

        $data = serialize( $data );

        // Can we compress the Meta Data Contents?

        if( function_exists( 'gzcompress' ) ) {
            $data = gzcompress( $data, 9 );
        }

        try {

            // Setting Up Writer Object

            $writer = new Writer(

                new Socket(

                    sprintf(

                            '%s/%s',

                            $this -> backendOptions -> meta -> path,
                            $this -> backendOptions -> meta -> file
                    ),

                    'w'
                )
            );

            // Writing Data

            $result = (bool) $writer -> write( $data );

            // Closing File Stream

            $writer -> getStream() -> close();

            return $result;

        } catch( WriterException $e ) {

            /**
             * @internal
             * As we are trying to create/update the Meta Data File, if we got an Exception
             * means the file exists but it's not writable or it doesn't exists
             * and its parent directory is not writable
             *
             * So the Meta Data File could not be created/updated and, when trying to load
             * it, the file will be regenerated anyway
             */
            return FALSE;
        }
    }
}
