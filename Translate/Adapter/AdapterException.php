<?php

namespace Next\Translate\Adapter;

/**
 * Translate Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class AdapterException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x000007C5, 0x000007F7 );

    // Missing Required Cache Key

    /**
     * Missing Required Cache Key
     *
     * @var integer
     */
    const NO_CACHE_KEY       =    0x000007C5;

    // Invalid GNU MO File Structure

    /**
     * Invalid GNU MO File
     *
     * @var integer
     */
    const INVALID_MO_FILE    =    0x000007C6;

    // Exception Messages

    /**
     * Missing Cache Key
     *
     * Without a Cache Key we're unable to cache Translation Data Structure
     *
     * @return Next\Translate\Adapter\AdapterException
     *   Exception for missing Cache Key
     */
    public static function missingCacheKey() {

        return new self(

            'You can\'t cache Translation Table without a key to define it.',

            self::NO_CACHE_KEY
        );
    }

    /**
     * Invalid GNU MO File
     *
     * GetText Adapter can only work with legitimate GNU MO Files
     *
     * @param string $filename
     *   Invalid MO Filename
     *
     * @return Next\Translate\Adapter\AdapterException
     *   Exception for invalid MO Files
     */
    public static function invalidMOFile( $filename ) {

        return new self(

            'File <strong>%s</strong> is not a GNU MO file',

            self::INVALID_MO_FILE,

            $filename
        );
    }
}
