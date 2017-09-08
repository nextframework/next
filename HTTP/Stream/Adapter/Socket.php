<?php

/**
 * HTTP Stream Socket Adapter Class | HTTP\Stream\Adapter\Socket.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream\Adapter;

use Next\HTTP\Stream\Context\Context;             # Stream Context Interface
use Next\HTTP\Stream\Context\SocketContext;       # Stream Context Socket ContextClass

/**
 * Defines an Adapter Class using regular FileSystem
 *
 * @package    Next\Stream
 */
class Socket extends AbstractAdapter {

    // Opening Modes Constants

    /**
     * Read-only
     *
     * <p>
     *     Open the stream for reading, placing the pointer at the beginning
     *     of the file
     * </p>
     *
     * @var string
     */
    const READ                   =   'rb';

    /**
     * Read and Write
     *
     * <p>
     *     Open the stream for reading and writing, placing the pointer at
     *     the beginning of the file
     * </p>
     *
     * @var string
     */
    const READ_WRITE                = 'r+b';

    /**
     * Write-only
     *
     * <p>
     *     Open the stream for writing, placing the pointer at the beginning
     *     of the file and truncating the file to zero length.
     * </p>
     *
     * <p>If the file does not exist, attempt to create it</p>
     *
     * @var string
     */
    const TRUNCATE_WRITE            = 'wb';

    /**
     * Read and Write
     *
     * <p>
     *     Open the stream for reading and writing, placing the pointer at
     *     the beginning of the file and truncating the file to zero length.
     * </p>
     *
     * <p>If the file does not exist, attempt to create it</p>
     *
     * @var string
     */
    const TRUNCATE_READ_WRITE       = 'w+b';

    /**
     * Append Write-only
     *
     * <p>
     *     Open the stream for writing, placing the pointer at the end
     *     of the file and truncating the file to zero length.
     * </p>
     *
     * <p>If the file does not exist, attempt to create it</p>
     *
     * @var string
     */
    const APPEND_WRITE              = 'ab';

    /**
     * Append Read and Write
     *
     * <p>
     *     Open the stream for writing, placing the pointer at the end
     *     of the file and truncating the file to zero length.
     * </p>
     *
     * <p>If the file does not exist, attempt to create it</p>
     *
     * @var string
     */
    const APPEND_READ_WRITE         = 'a+b';

    /**
     * Exclusive Read
     *
     * <p>
     *     Create and open the stream for writing only, placing the pointer in
     *     the beginning of file
     * </p>
     *
     * <p>
     *     If the file does not exist doesn't try to create it but the opening
     *     routine returns FALSE em emits an E_WARNING error
     * </p>
     *
     * @var string
     */
    const EXCLUSIVE_WRITE           = 'xb';

    /**
     * Exclusive Read and Write
     *
     * <p>
     *     Create and open the stream for writing only, placing the pointer in
     *     the beginning of file
     * </p>
     *
     * <p>
     *     If the file does not exist doesn't try to create it but the opening
     *     routine returns FALSE em emits an E_WARNING error
     * </p>
     *
     * @var string
     */
    const EXCLUSIVE_READ_WRITE      = 'x+b';

    /**
     * Conditional Read
     *
     * <p>
     *     The same as WRITE ONLY, but the file is not truncated nor any error
     *     is raised if the file already exists (opposed to EXCLUSIVE)
     * </p>
     *
     * <p>
     *     Useful for advisory locks before attempt to modify the file.
     *
     *     E.g: A simplified but equivalent implementation of
     *     \Next\HTTP\Stream\Writer::write() that prevents a zero-length data to
     *     be written, warning the user about possible data loss.
     *
     *     ````
     *         function write( $string, $length = NULL ) {
     *
     *             $handler = fopen( './file.txt', 'w' );
     *
     *             if( $length !== NULL ) {
     *
     *                 if( (int) $length === 0 ) {
     *                     throw new Exception(
     *                         'Using zero-length when writing data, will result in a blank file/empty stream'
     *                     );
     *                 }
     *
     *                 $bytes = fwrite( $handler, $string, $length );
     *
     *             } else {
     *
     *                 $bytes = fwrite( $handler, $string );
     *             }
     *
     *             return $bytes;
     *         }
     *
     *         try {
     *
     *             var_dump( write( 'some text', 0 ) );
     *
     *         } catch( Exception $e ) {
     *
     *             echo $e -> getMessage();
     *         }
     *     ````
     *
     * Because of the WRITE mode (w) the code above will output the Exception
     * message, but the file named <strong>file.txt</strong> will already be emptied
     * not because of fwrite(), but because of fopen() already truncated the file
     *
     * By using the "c" or "c+" mode (see below), this does not happen.
     */
    const CONDITIONAL_WRITE         = 'cb';

    /**
     * Conditional Read and Write
     *
     * <p>
     *     The same as READ and WRITE (w+), but the file is not truncated nor any error
     *     is raised if the file already exists (opposed to EXCLUSIVE)
     * </p>
     *
     * @var string
     */
    const CONDITIONAL_READ_WRITE    = 'c+b';

    /**
     * Opening Mode
     *
     * @var string $mode
     */
    private $mode;

    /**
     * HTTP Stream Socket Adapter Constructor
     *
     * @param string $filename
     *  File/URL to be opened
     *
     * @param string|optional $mode
     *  Opening Mode
     *
     * @param \Next\HTTP\Stream\Context\Context|optional $context
     *  Optional Stream Context to be used
     *
     * @throws \Next\HTTP\Stream\Adapter\AdapterException
     *  Chosen mode is invalid
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for Caching Schema
     */
    public function __construct( $filename, $mode = self::READ, Context $context = NULL, $options = NULL ) {

        parent::__construct( $options );

        $this -> filename = trim( $filename );

        $mode = sprintf( '%sb', strtolower( trim( str_replace( 'b', '', $mode ) ) ) );

        if( ! in_array( $mode, $constants = $this -> getClass() -> getConstants() ) ) {
            throw AdapterException::invalidOpeningMode( $constants );
        }

        $this -> mode =& $mode;

        $this -> context = ( ! is_null( $context ) ? $context : new SocketContext );
    }

    // Adapter Interface Methods Implementation

    /**
     * Open a File (or URL)
     *
     * @throws \Next\HTTP\Stream\Adapter\AdapterException
     *  Unable to open stream
     */
    public function open() {

        try {

            $this -> isOpened();

        } catch( AdapterException $e ) {

            // Checking Opening Permissions

            $this -> checkPermissions();

            // Note: We are using the error suppression just because we want ONLY our Exception

            $this -> stream = @fopen(

                $this -> filename, $this -> mode,

                FALSE, $this -> context -> getContext()
            );

            // Is that an error?

            if( $this -> stream == FALSE ) {

                throw AdapterException::unableToOpen( $this -> filename );
            }
        }
    }

    /**
     * Close opened Stream
     *
     * @return boolean
     *  TRUE on success and FALSE otherwise
     */
    public function close() {

        try {

            $this -> isOpened();

            return ( fclose( $this -> stream ) !== FALSE );

        } catch( AdapterException $e ) {

            return FALSE;
        }
    }

    /**
     * Test if Stream has achieved the End of File
     *
     * @return boolean
     *  TRUE if EOF (End-of-File) was achieved and FALSE otherwise
     *
     * @throws \Next\HTTP\Stream\Adapter\AdapterException
     *  Stream is not opened
     */
    public function eof() {

        if( $this -> isOpened() ) {
            return feof( $this -> stream );
        }

        return FALSE;
    }

    /**
     * Tell the current position of Stream Pointer
     *
     * @return integer|boolean
     *  Pointer position if it could be retrieved and FALSE otherwise
     *
     * @throws \Next\HTTP\Stream\Adapter\AdapterException
     *  Pointer position could be be retrieved
     */
    public function tell() {

        if( $this -> valid() ) {

            $tell = ftell( $this -> stream );

            if( $tell === FALSE ) {
                throw AdapterException::unableToTell();
            }

            return $tell;
        }

        return FALSE;
    }

    /**
     * Get the size of Stream
     *
     * @return integer|boolean
     *  Returns the length of the Stream if it's valid and FALSE otherwise
     *
     * @see \Next\HTTP\Stream\Adapter\Adapter::valid()
     */
    public function size() {

        if( $this -> valid() ) {
            return strlen( stream_get_contents( $this -> stream ) );
        }

        return FALSE;
    }

    /**
     * Check if Stream was opened, by testing its Resource
     *
     * @return boolean
     *  Always TRUE, because if Stream is not opened an Exception
     *  will be thrown
     *
     * @throws \Next\HTTP\Stream\Adapter\AdapterException
     *  Stream is not a valid Resource.
     */
    public function isOpened() {

        if( ! is_resource( $this -> stream ) ) {

            throw AdapterException::logic(

                'Stream must be opened before perform any operation over it'
            );

            return FALSE;
        }

        return TRUE;
    }

    /**
     * Get Stream Meta Data
     *
     * @return array
     *  Stream Metadata
     */
    public function getMetaData() {

        return ( $this -> valid() ?
            stream_get_meta_data( $this -> stream ) : [] );
    }

    // SeekableIterator Interface Method Implementation

    /**
     * Seek Stream to given position
     *
     * @param integer $position
     *  Offset to seek Stream Pointer
     *
     * @return integer
     *  If Stream is valid and fseek() is a success, zero will be returned
     *
     *  If Stream is not valid, -1 will be returned, just as
     *  fomality
     *
     * @throws \Next\HTTP\Stream\Adapter\AdapterException
     *  Unable to seek a position in Stream
     */
    public function seek( $position ) {

        if( $this -> valid() ) {

            if( fseek( $this -> stream, $position ) == -1 ) {
                throw AdapterException::unableToSeek();
            }

            return 0;
        }

        return -1;
    }

    // Iterator Interface Methods Implementation

    /**
     * Return the current element
     *
     * @return string|boolean
     *
     *  If current element can be read, a string will be returned
     *
     *  Otherwise, FALSE will.
     */
    public function current() {
        return ( $this -> valid() ? fgets( $this -> stream ) : FALSE );
    }

    /**
     * Return the key of the current element
     *
     * @return integer|boolean
     *
     *  If Pointer position could be retrieved, it will be returned
     *
     *  If it couldn't or if Stream is not valid, FALSE will.
     */
    public function key() {

        if( $this -> valid() ) {

            try {

                return $this -> tell();

            } catch( AdapterException $e ) {

                return FALSE;
            }
        }

        return FALSE;
    }

    /**
     * Move forward to next element
     *
     * @return ineteger|NULL
     *  If Pointer position can be retrieved and Stream can be seeked to
     *  its position, zero will be returned, as returned by fseek()
     *
     *  Otherwise, NULL will
     */
    public function next() {

        try {

            return ( $this -> valid() ? $this -> seek( $this -> tell() ) : NULL );

        } catch( AdapterException $e ) {

            return NULL;
        }
    }

    /**
     * Rewind the Iterator to the first element
     *
     * <p>In Stream context, moves pointer to beginning of file</p>
     *
     * @return boolean
     *  TRUE if Stream is valid and Pointer could be rewinded.
     *
     *  FALSE otherwise
     */
    public function rewind() {
        return ( $this -> valid() && rewind( $this -> stream ) );
    }

    /**
     * Check if current position is valid.
     *
     * This method is called after Iterator::rewind() and Iterator::next()
     * to check if the current position still valid
     *
     * This a "Interface Alias" for \Next\HTTP\Stream\Adapter\Adapter::eof()
     *
     * @return boolean
     *  TRUE if EOF (End-of-File) was NOT achieved and FALSE otherwise
     */
    public function valid() {

        try {

            return ( ! $this -> eof() );

        } catch( AdapterException $e ) {

            return FALSE;
        }
    }

    // Auxiliary Methods

    /**
     * Check if we have permissions to open the Stream
     * in according to given Opening's Mode
     *
     * @return boolean
     *  Always TRUE, because on failure an Exception is thrown
     *
     * @throws \Next\HTTP\Stream\Adapter\AdapterException
     *  Stream is not readable and opening more requires readability
     *
     * @throws \Next\HTTP\Stream\Adapter\AdapterException
     *  Stream is not writable (nor its parnt directory) and opening mode
     *  required writability
     */
    private function checkPermissions() {

        // Setting Up FileInfo Object

        $info = new \SplFileInfo( $this -> filename );

        switch( $this -> mode ) {

            // Checking Readability

            case self::READ:
            case self::READ_WRITE:

            case self::TRUNCATE_READ_WRITE:
            case self::APPEND_READ_WRITE:

                if( $info -> isFile() && ! $info -> isReadable() ) {
                    throw AdapterException::unableToRead( $this -> filename );
                }

            break;

            // Checking Writability

            case self::READ_WRITE:

            case self::TRUNCATE_WRITE:
            case self::TRUNCATE_READ_WRITE:

            case self::APPEND_WRITE:
            case self::APPEND_READ_WRITE:

            case self::CONDITIONAL_WRITE:
            case self::CONDITIONAL_READ_WRITE:

                if( $info -> isFile() && ! $info -> isWritable() ) {

                    throw AdapterException::unableToWrite( $this -> filename );

                } else {

                    $path = new \SplFileInfo( $info -> getPath() );

                    if( ! $path -> isWritable() ) {
                        throw AdapterException::unableToWrite( $this -> filename );
                    }
                }

            break;

            case self::EXCLUSIVE_WRITE:
            case self::EXCLUSIVE_READ_WRITE:

                // File already exists

                if( $info -> isFile() ) {

                    throw AdapterException::unableToExclusivelyWrite( $this -> filename );

                } else {

                    $path = new \SplFileInfo( $info -> getPath() );

                    if( ! $path -> isWritable() ) {
                        throw AdapterException::unableToWrite( $this -> filename );
                    }
                }

            break;
        }

        return TRUE;
    }
}