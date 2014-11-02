<?php

namespace Next\Translate;

/**
 * Translate Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class TranslateException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x000007F8, 0x0000082A );

    /**
     * Missing Stream Adapter
     *
     * @var integer
     */
    const NO_ADAPTER           =    0x000007F8;

    /**
     * Locale File not Found
     *
     * @var integer
     */
    const FILE_NOT_FOUND       =    0x000007F9;

    /**
     * Locale File is not readable
     *
     * @var integer
     */
    const FILE_NOT_READABLE    =    0x000007FA;

    // Exception Messages

    /**
     * Missing HTTP Stream Adapter
     *
     * @return Next\Translate\TranslateException
     *  Exception for missing HTTP Stream Adapter
     */
    public static function noAdapter() {

        return new self(

            'Translation Adapter must be set before add any Locale File',

            self::NO_ADAPTER
        );
    }

    /**
     * Locale File not Found
     *
     * @param string $filename
     *  Locale Filename
     *
     * @return Next\Translate\TranslateException
     *  Exception for missing Locale File
     */
    public static function fileNotFound( $filename ) {

        return new self(

            'File <strong>%s</strong> was not found',

            self::FILE_NOT_FOUND,

            $filename
        );
    }

    /**
     * Locale File not Readable
     *
     * @param string $filename
     *  Locale Filename
     *
     * @return Next\Translate\TranslateException
     *  Exception for Locale File unreadability
     */
    public static function fileNotReadable( $filename ) {

        return new self(

            'File <strong>%s</strong> is not readable',

            self::FILE_NOT_READABLE,

            $filename
        );
    }
}
