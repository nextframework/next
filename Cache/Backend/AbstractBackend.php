<?php

namespace Next\Cache\Backend;

use Next\Components\Interfaces\Parameterizable;    # Parameterizable Interface
use Next\Cache\CacheException;                     # Cache Exception Class
use Next\Components\Object;                        # Object Class
use Next\Components\Parameter;                     # Parameter Class
use Next\Cache\Meta;                               # Cache Metadata Class

/**
 * Cache Backend Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractBackend extends Object implements Backend {

    // Available Hashing Algorithms

    /**
     * MD5 Hash Algorithm
     *
     * @var string
     */
    const MD5      = 'md5';

    /**
     * CRC32 Hash Algorithm
     *
     * @var string
     */
    const CRC32    = 'crc32';

    /**
     * SHA1 Hash Algorithm
     *
     * @var string
     */
    const SHA1     = 'sha1';

    /**
     * SHA512 Hash Algorithm
     *
     * @var string
     */
    const SHA512   = 'sh512';

    /**
     * Adler32 Hash Algorithm
     *
     * @var string
     */
    const ADLER32  = 'adler32';

    /**
     * Backend Default Common Options
     *
     * @var array $defaultOptions
     */
    private $defaultOptions = array(

        'lifeTime'  => 86164, // One sideral day (rounded): 23h 56m 4.09s

        'metadata'  => array(

                'path' => 'Cache',
                'file' => 'META'
        ),

        'security'  => array(

                'testValidity'    => TRUE,
                'hashAlgorithm'   => self::MD5,
                'removeCorrupted' => TRUE
        )
    );

    /**
     * Backend Options
     *
     * O cuidado em identificar pontos críticos na necessidade de renovação
     * processual prepara-nos para enfrentar situações atípicas decorrentes
     * do sistema de participação geral.
     *
     * @var Next\Components\Parameter $options
     */
    protected $options;

    /**
     * Metadata Accessor
     *
     * @var Next\Cache\Meta $meta
     */
    protected $meta;

    /**
     * Backend Constructor
     *
     * @param mixed|optional $options
     *
     *   <br />
     *
     *   <p>
     *       List of Options to affect Cache Backend. Acceptable values are:
     *   </p>
     *
     *   <p>
     *
     *       <ul>
     *
     *           <li>Associative and multidimensional array</li>
     *
     *           <li>
     *
     *               An {@link http://php.net/manual/en/reserved.classes.php stdClass Object}
     *
     *           </li>
     *
     *           <li>A well formed Next\Components\Parameter Object</li>
     *
     *       </ul>
     *
     *   </p>
     *
     *   <p>The arguments taken in consideration are:</p>
     *
     *   <p>
     *
     *       <ul>
     *
     *           <li>
     *
     *               <p><strong>lifetime</strong></p>
     *
     *               <p>Defines the max age for Cached Data</p>
     *
     *               <p>
     *                   Default Value: <strong>86164</strong>
     *                   (one sideral day - 23h 56m 4.09s, rounded)
     *               </p>
     *
     *           </li>
     *
     *           <li>
     *
     *               <p><strong>metadata</strong></p>
     *
     *               <p>
     *                   A subgroup of options containing one or more of the
     *                   following options:
     *               </p>
     *
     *               <ul>
     *
     *                   <li>
     *
     *                       <p><strong>path</strong></p>
     *
     *                       <p>Path where Metadata file will be located</p>
     *
     *                   </li>
     *
     *
     *                   <li>
     *
     *                       <p><strong>file</strong></p>
     *
     *                       <p>Metadata Filename</p>
     *
     *                   </li>
     *
     *               </ul>
     *
     *           <li>
     *
     *               <p><strong>security</strong></p>
     *
     *               <p>
     *                   A subgroup of options containing one or more of the
     *                   following options:
     *               </p>
     *
     *               <ul>
     *
     *                   <li>
     *
     *                       <p><strong>testValidity</strong></p>
     *
     *                       <p>Defines whether or not the Cache Data will
     *                       have its computed hash verified.</p>
     *
     *                       <p>Default Value: <strong>TRUE</strong>.</p>
     *
     *                   </li>
     *
     *                   <li>
     *
     *                       <p><strong>hashAlgorithm</strong></p>
     *
     *                       <p>
     *                           The Hash Algorithm used for Cache Data
     *                           Integrity Check
     *                       </p>
     *
     *                       <p>Default Value: <strong>md5</strong></p>
     *
     *                   </li>
     *
     *                   <li>
     *
     *                       <p><strong>removeCorrupted</strong></p>
     *
     *                       <p>
     *                           Defines whether or not a corrupted Cached
     *                           Data will be automatically removed.
     *                       </p>
     *
     *                       <p>Default Value: <strong>TRUE</strong></p>
     *
     *                   </li>
     *
     *               </ul>
     *
     *           </li>
     *
     *       </ul>
     *
     *   </p>
     *
     * @see Next\Components\Parameter
     */
    public function __construct( $options = NULL ) {

        // Checking Backend Requirements

        $this -> checkRequirements();

        // Setting Up Options Object

        $this -> options = new Parameter( $this -> defaultOptions, $this -> setOptions(), $options );

        // Setting Up Metadata Accessors Object

        $this -> metadata = new Meta( $this );

        // Checking Options Integrity

        $this -> checkIntegrity();

        // Extra Initialization

        $this -> init();
    }

    /**
     * Additional Initialization. Must be overwritten
     */
    protected function init() {}

    /**
     * Add more lifetime to Cached Data
     *
     * <p>
     *     Touches the Cache File, trying to give it an extra lifetime
     * </p>
     *
     * <p>
     *     This is a task common to all Cache Backends
     * </p>
     *
     * @param string $key
     *   Cache Key
     *
     * @param integer $extra
     *   Extra Lifetime
     *
     * @return boolean
     *   TRUE if we were able to touch the Cache File and FALSE otherwise
     */
    public function touch( $key, $extra ) {

        try {

            $data = $this -> load( $key );

            if( $data !== FALSE ) {

                if( $this -> add( $key, $data, (int) $extra, TRUE ) !== FALSE ) {

                    return TRUE;
                }
            }

            return FALSE;

        } catch( CacheException $e ) {

            return FALSE;
        }
    }

    // Parameterizable Interface Methods Implementation

    /**
     * Set Up Backend Options
     *
     * <p>
     *     Overwritable because not all Cache Backends have specific options
     *     to be set
     * </p>
     */
    protected function setOptions() {}

    /**
     * Get Backend Options
     *
     * @return Next\Components\Parameter
     *   Parameter Object with merged options
     */
    public function getOptions() {
        return $this -> options;
    }

    // Auxilar Methods

    /**
     * Generate a File ID
     *
     * @param string $filename
     *   Cache Filename
     *
     * @param string|optional $prefix
     *   An optional prefix to distinguish multiple Cache Files
     *
     * @return string
     *   A Cache File ID made an optional prefix and the MD5 hash of the real filename
     */
    protected function generateID( $filename, $prefix = NULL ) {

        $prefix = ( ! is_null( $prefix ) ? sprintf( '%s--', str_replace( '--', '', $prefix ) ) : NULL );

        return sprintf( '%s%s', $prefix, md5( trim( (string) $filename ) ) );
    }

    /**
     * Generate Data Hash
     *
     * <p>
     *     Generate Hash from Cached Data in order to allow Cache Data
     *     Integrity Check.
     * </p>
     *
     * <p>
     *     Hash Algorithm is the same defined in <strong>hashAlgorithm</strong>
     *     option
     * </p>
     *
     * @param string $data
     *   Data to calculate proper hash
     *
     * @return string
     *   Hash of Cached Data according to chosen algorithm
     */
    protected function hash( $data ) {

        $data = (string) $data;

        switch( (string) $this -> options -> hashAlgorithm ) {

            case self::CRC32:      return crc32( $data );             break;

            case self::SHA1:       return sha1( $data );              break;

            case self::SHA512:     return hash( 'sha512', $data );    break;

            case self::ADLER32:    return hash( 'adler32', $data );   break;

            case self::MD5:
                default:           return md5( $data );               break;
        }
    }

    /**
     * Write Cache Metadata
     *
     * @param string $key
     *   Cache Key to describe what is being cached
     *
     * @param string|optional $data
     *   Data to Cache
     *
     * @param integer|optional $ttl
     *   Optional Lifetime
     *
     * @param boolean|optional $isTouching
     *   Flag to condition when we're touching the Cache File
     *   in order to give it an extra lifetime
     *
     * @return array
     *   Metadata array with Information about Caches Data
     */
    protected function metadata( $key = NULL, $data, $ttl = NULL, $isTouching = FALSE ) {

        $meta = array();

        $data = (string) $data;

        // Cache Control Information

        $cType =& $this -> options -> hashAlgorithm; # Shortening

        $meta['cacheControl'] = $cType;

        $meta['hash'] = ( ! is_null( $data ) ? $this -> hash( $data, $cType ) : FALSE );

        // Cache Features Information

            // Compression is only available for File Backend

        if( isset( $this -> options -> compression ) ) {

            $meta['compressed'] = (bool) $this -> options -> compression -> enabled;

        } else {

            $meta['compressed'] = FALSE;
        }

        // Timestamp Information

        $ttl = (int) $ttl;

        $meta['mTime']      = time();
        $meta['lifeTime']   = ( $ttl > 0 ? $ttl : $this -> options -> lifeTime );

        // Are we touching the Cache?

        if( $isTouching !== FALSE ) {

            // Trying to retrieve current Meta Data...

            try {

                $cMeta = $this -> meta -> load( $key );

            } catch( CacheException $e ) {

                $cMeta = FALSE;
            }

            if( $cMeta != FALSE ) {

                $lifetime = $cMeta['lifeTime'] + $ttl;

                // ... to update Cache's Lifetime

                $meta['lifeTime'] = ( $lifetime <= 0 ? $cMeta['lifeTime'] : $lifetime );
            }
        }

        // Expiration Time

        $meta['expire'] = $meta['mTime'] + $meta['lifeTime'];

        return $meta;
    }

    /**
     * Check Options Integrity
     *
     * <p>
     *     Could be abstract, because each Backend should check in a different
     *     way, different types of options, but allowing it to be overwritten,
     *     we can check if our common options were not corrupted in any form.
     * </p>
     *
     * <p>
     *     Of course, the child classes must inherit these checks before check
     *     their own, but this is the least of the problems
     * </p>
     *
     * @throws Next\Cache\CacheException
     *   Invalid or unsupported Hash Algorithm
     */
    protected function checkIntegrity() {

        $constants = $this -> getClass() -> getConstants();

        if( ! in_array( $this -> options -> security -> hashAlgorithm, $constants ) ) {

            throw BackendException::invalidHashType();
        }
    }

    // Abstract Methods Definition

    /**
     * Check Backend Requirements
     *
     * E.g.: APC Backend requires APC extension as part of loaded extensions
     */
    abstract protected function checkRequirements();
}
