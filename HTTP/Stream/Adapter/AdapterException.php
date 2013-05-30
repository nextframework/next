<?php

namespace Next\HTTP\Stream\Adapter;

/**
 * Stream Adapter Exception Class
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
    protected $range = array( 0x000004FB, 0x0000052D );

    /**
     * Invalid Opening Mode
     *
     * @var integer
     */
    const INVALID_OPENING_MODE    = 0x000004FB;

    /**
     * Unable to Open Stream
     *
     * @var integer
     */
    const UNABLE_TO_OPEN          = 0x000004FC;

    /**
     * Unable to Read Stream
     *
     * @var integer
     */
    const UNABLE_TO_READ          = 0x000004FD;

    /**
     * Unable to Write on Stream
     *
     * @var integer
     */
    const UNABLE_TO_WRITE         = 0x000004FE;

    // Unable to Tell (no kidding :P)

    /**
     * Unable to tell pointer position
     *
     * @var integer
     */
    const UNABLE_TO_TELL          = 0x000004FF;

    // Unable to Seek

    /**
     * Unable to seeks pointer to a position
     *
     * @var integer
     */
    const UNABLE_TO_SEEK          = 0x00000500;

    // Exception Messages

    /**
     * Invalid Stream Opening Mode
     *
     * @param array $validModes
     *   Valid Opening Modes
     *
     * @return Next\HTTP\Stream\Adapter\AdapterException
     *   Exception for invalid Stream Opening Mode
     */
    public static function invalidOpeningMode( array $validModes ) {

        return new self(

            'Invalid Opening Mode. Allowed Modes are: <strong>%s</strong>',

            self::INVALID_OPENING_MODE,

            implode( '</strong>, <strong>', $validModes )
        );
    }

    /**
     * Unable to open a File/URL Stream
     *
     * @param string $filename
     *   File/URL being opened
     *
     * @return Next\HTTP\Stream\Adapter\AdapterException
     *   Exception for Stream opening failure
     */
    public static function unableToOpen( $filename ) {

        return new self(

            'Unable to open a File/URL Stream to <strong>%s</strong>',

            self::UNABLE_TO_OPEN,

            $filename
        );
    }

    /**
     * Unable to Read Stream
     *
     * @note Read Stream is different from Read Bytes from Stream
     *
     * @param string $filename
     *   File being opened
     *
     * @return Next\HTTP\Stream\Reader\ReaderException
     *   Exception for Stream readability failure
     */
    public static function unableToRead( $filename ) {

        return new self(

            'File <strong>%s</strong> is not readable',

            self::UNABLE_TO_READ,

            $filename
        );
    }

    /**
     * Unable to Write on Stream
     *
     * @note Write on Stream is ifferent from Write Data on Stream
     *
     * @param string $filename
     *   File being opened
     *
     * @return Next\HTTP\Stream\Writer\WriterException
     *   Exception for Stream writability failure
     */
    public static function unableToWrite( $filename ) {

        return new self(

            'File <strong>%s</strong> or its Parent Directory is not writable',

            self::UNABLE_TO_WRITE,

            $filename
        );
    }

    /**
     * Unable to tell Stream Pointer position
     *
     * @return Next\HTTP\Stream\Adapter\AdapterException
     *   Exception for Stream Pointer retrieval failure
     */
    public static function unableToTell() {

        return new self(

            'Stream Pointer could not be retrieved',

            self::UNABLE_TO_TELL
        );
    }

    /**
     * Unable to seek to Stream Pointer to a position
     *
     * @return Next\HTTP\Stream\Adapter\AdapterException
     *   Exception for Stream Pointer seeking failure
     */
    public static function unableToSeek() {

        return new self(

            'Fail when trying to seek File Stream Pointer',

            self::UNABLE_TO_SEEK
        );
    }
}
