<?php

namespace Next\Translate\Adapter;

use Next\Cache\CacheException;     # Cache Exception Class
use Next\Cache\Backend\Backend;    # Cache Backend Interface

/**
 * GetText Adapter Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class GetText extends AbstractAdapter {

    // Endianness Magic Numbers

    /**
     * Big Endian Magic Number
     *
     * @var string
     */
    const BIG_ENDIAN       = 'de120495';

    /**
     * Little Endian Magic Number
     *
     * @var string
     */
    const LITTLE_ENDIAN    = '950412de';

    /**
     * Is a Big Endian MO File?
     *
     * @var boolean $isBigEndian
     */
    private $isBigEndian = FALSE;

    // Abstract Methods Implementation

    /**
     * Get Translation Table
     *
     * @param string|optional $key
     *   Cache Key
     *
     * @return array
     *   Table of keys and their associated translations
     */
    public function getTranslationTable( $key = NULL ) {

        /**
         * \internal
         * No Cache Key provided or no Cache Backend provided.
         *
         * Let's load, load, load and load again
         */
        if( is_null( $key ) || $this -> cacheBackend instanceof Backend ) {
            return $this -> load();
        }

        // Loading from Cache

        try {

            $translationTable = $this -> cacheBackend -> load( $key );

            /**
             * \internal
             * If fail when loading Cached Data or its illegible (as array)
             * let's analyze MO File again.
             */
            if( ! $translationTable || ! is_array( $translationTable ) ) {

                // Illegible or Missing Cached Data? Let's re-cache it

                return $this -> load( $key );
            }

            return $translationTable;

        } catch( CacheException $e ) {

            /**
             * \internal
             * If the Cached File doesn't exists, and for some reason we're
             * unable to create it (writing impossibilities), we have to
             * list content again (and again, and again...)
             */
            return $this -> load( $key );
        }
    }

    /**
     * Reads MO Stream File
     *
     * MO Files' Structure can be represented as follows:
     *
     * <code>
     *         byte
     *               +------------------------------------------+
     *            0  | magic number = 0x950412de                |
     *               |                                          |
     *            4  | file format revision = 0                 |
     *               |                                          |
     *            8  | number of strings                        |  == N
     *               |                                          |
     *           12  | offset of table with original strings    |  == O
     *               |                                          |
     *           16  | offset of table with translation strings |  == T
     *               |                                          |
     *           20  | size of hashing table                    |  == S
     *               |                                          |
     *           24  | offset of hashing table                  |  == H
     *               |                                          |
     *               .                                          .
     *               .    (possibly more entries later)         .
     *               .                                          .
     *               |                                          |
     *            O  | length & offset 0th string  ----------------.
     *        O + 8  | length & offset 1st string  ------------------.
     *                ...                                    ...   | |
     *  O + ((N-1)*8)| length & offset (N-1)th string           |  | |
     *               |                                          |  | |
     *            T  | length & offset 0th translation  ---------------.
     *        T + 8  | length & offset 1st translation  -----------------.
     *                ...                                    ...   | | | |
     *  T + ((N-1)*8)| length & offset (N-1)th translation      |  | | | |
     *               |                                          |  | | | |
     *            H  | start hash table                         |  | | | |
     *                ...                                    ...   | | | |
     *    H + S * 4  | end hash table                           |  | | | |
     *               |                                          |  | | | |
     *               | NUL terminated 0th string  <----------------' | | |
     *               |                                          |    | | |
     *               | NUL terminated 1st string  <------------------' | |
     *               |                                          |      | |
     *                ...                                    ...       | |
     *               |                                          |      | |
     *               | NUL terminated 0th translation  <---------------' |
     *               |                                          |        |
     *               | NUL terminated 1st translation  <-----------------'
     *               |                                          |
     *                ...                                    ...
     *               |                                          |
     *               +------------------------------------------+
     * </code>
     *
     * Information found at: http://www.gnu.org/software/hello/manual/gettext/MO-Files.html
     *
     * @param string|optional $cacheKey
     *   Cache Key
     *
     * @return array
     *   Table of keys and their associated tranlations
     *
     * @throws
     *
     *   @link Translate::Adapter::AdapterException @endlink
     *
     *   Thrown if we have a Cache Backend to work with, but no Cache Key was provided
     */
    private function load( $cacheKey = NULL ) {

        // Detect File Endianness

        $this -> detectEndianness();

        // First bytes are for File Revision. Unused, but we have read anyways

        $this -> read( 1 );

        // N...O...T...S...H... (see table above)

        $notsh    = array(

                        $this -> read( 1 ),
                        $this -> read( 1 ),
                        $this -> read( 1 ),
                        $this -> read( 1 ),
                        $this -> read( 1 )
                    );

        list( $N, $O, $T, $S, $H ) = $notsh;

        // Shortening Stream Adapter

        $adapter = $this -> reader -> getAdapter();

        // Reading Original Strings Offsets

        $adapter -> seek( $O );

        $originalOffsets = $this -> read( 2 * $N, FALSE );

        // Reading Translation Strings Offsets

        $adapter -> seek( $T );

        $translationOffsets = $this -> read( 2 * $N, FALSE );

        // Building

        $headers = NULL;

        $translationTable = array();

        // $i += 1 is faster than ++$i

        for( $i = 0; $i < $N; $i += 1 ) {

            if( $originalOffsets[ $i * 2 + 1 ] == 0 ) {

               // Reading Headers

               $adapter -> seek( $translationOffsets[ $i * 2 + 2 ] );

               $headers = $this -> reader -> read( $translationOffsets[ $i * 2 + 1 ] );

            } else {

               // Searching the String Key...

               $adapter -> seek( $originalOffsets[ $i * 2 + 2 ] );

               $key = $this -> reader -> read( $originalOffsets[ $i * 2 + 1 ] );

               // ... and its matching Value

               $adapter -> seek( $translationOffsets[ $i * 2 + 2 ] );

               $value = $this -> reader -> read( $translationOffsets[ $i * 2 + 1 ] );

               // Recording Data

               $translationTable[ $key ] = $value;
            }
        }

        // Build Headers

        $translationTable = array_merge( $this -> buildHeaders( $headers ), $translationTable );

        // Can we cache results?

        if( $this -> cacheBackend instanceof Backend ) {

            if( is_null( $cacheKey ) ) {

                throw AdapterException::missingCacheKey();
            }

            $this -> cacheBackend -> add( $cacheKey, $translationTable );
        }

        return $translationTable;
    }

    // Auxiliary Methods

    /**
     * Detect Endianness of MO File
     *
     * Indirectly, check if Stream is really about a MO File
     *
     * @throws
     *
     *   @link Translate::Adapter::AdapterException @endlink
     *
     *   Thrown if file of given Stream is not neither Big or Little Endian
     */
    private function detectEndianness() {

        $this -> reader -> getAdapter() -> seek( 0 );

        // Reading Magic Number

        $magicNumber = $this -> read( 1 );

        // Checking

        switch( dechex( $magicNumber ) ) {

            case self::LITTLE_ENDIAN:
                $this -> isBigEndian = FALSE;
            break;

            case self::BIG_ENDIAN:
                $this -> isBigEndian = TRUE;
            break;

            default:

                throw AdapterException::invalidMOFile(

                    (string) $this -> reader -> getAdapter() -> getFilename()
                );

            break;
        }
    }

    /**
     * Parse and build found headers
     *
     * @param string $headers
     *   File Headers
     *
     * @return array
     *   MO File Metadata
     */
    private function buildHeaders( $headers ) {

        $headers = explode( "\n", $headers );

        $meta  = array();

        foreach( $headers as $key => $value ) {

            if( ! empty( $value ) ) {

                $key      = substr( $value, 0, strpos( $value, ':' ) );
                $value    = substr( $value, strpos( $value, ':' ) + 1 );

                $meta[ $key ] = trim( $value );
            }
        }

        return array( 'META' => $meta );
    }

    /**
     * Read unpacked data in according to detected Endianness
     *
     * @param integer $bytes
     *   Number of bytes to read
     *
     * @param boolean $shift
     *   Defines whether or not read bytes will be shifted off
     *
     * @return integer
     *   Bytes read
     */
    private function read( $bytes, $shift = TRUE ) {

        $result = unpack(

                      sprintf( '%s%d', ( $this -> isBigEndian ? 'N' : 'V' ), $bytes ),

                      $this -> reader -> read( $bytes * 4 )
                  );

        return ( $shift ? array_shift( $result ) : $result );
    }
}
