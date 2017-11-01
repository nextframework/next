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

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;
use Next\Exception\Exceptions\RuntimeException;

use Next\Validation\Verifiable;                # Verifiable Interface
use Next\HTTP\Stream\Context\Context;          # Stream Context Interface
use Next\HTTP\Stream\Context\SocketContext;    # Stream Context Socket ContextClass

/**
 * An HTTP Stream Adapter with Stream Sockets (FileSystem)
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Exception\Exceptions\RuntimeException
 *             Next\Validation\Verifiable
 *             Next\HTTP\Stream\Adapter\AbstractAdapter
 *             SplFileInfo
 */
class Socket extends AbstractAdapter implements Verifiable {

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
     * Read and Write.
     * Open the stream for reading and writing, placing the pointer at the
     * beginning of the file
     *
     * @var string
     */
    const READ_WRITE = 'r+b';

    /**
     * Write-only.
     * Open the stream for writing, placing the pointer at the beginning of the
     * file and truncating the file to zero length.
     *
     * If the file does not exist, attempt to create it
     *
     * @var string
     */
    const TRUNCATE_WRITE            = 'wb';

    /**
     * Read and Write.
     * Open the stream for reading and writing, placing the pointer at the
     * beginning of the file and truncating the file to zero length.
     *
     * If the file does not exist, attempt to create it
     *
     * @var string
     */
    const TRUNCATE_READ_WRITE = 'w+b';

    /**
     * Append Write-only.
     * Open the stream for writing, placing the pointer at the end of the file
     * and truncating the file to zero length.
     *
     * If the file does not exist, attempt to create it
     *
     * @var string
     */
    const APPEND_WRITE = 'ab';

    /**
     * Append Read and Write.
     * Open the stream for writing, placing the pointer at the end of the file
     * and truncating the file to zero length.
     *
     * If the file does not exist, attempt to create it
     *
     * @var string
     */
    const APPEND_READ_WRITE = 'a+b';

    /**
     * Exclusive Write.
     * Create and open the stream for writing only, placing the pointer in the
     * beginning of file.
     *
     * If the file does not exist doesn't try to create it but the opening
     * routine returns FALSE em emits an E_WARNING error
     *
     * @var string
     */
    const EXCLUSIVE_WRITE = 'xb';

    /**
     * Exclusive Read and Write.
     * Create and open the stream for writing only, placing the pointer in the
     * beginning of file.
     *
     * If the file does not exist doesn't try to create it but the opening
     * routine returns FALSE em emits an E_WARNING error
     *
     * @var string
     */
    const EXCLUSIVE_READ_WRITE = 'x+b';

    /**
     * Conditional Read.
     * The same as WRITE ONLY, but the file is not truncated nor any error is
     * is raised if the file already exists (opposed to EXCLUSIVE).
     *
     * Useful for advisory locks before attempt to modify the file.
     *
     * E.g: A simplified but equivalent implementation of
     * `\Next\HTTP\Stream\Writer::write()` that prevents a zero-length data to
     * be written, warning the user about possible data loss.
     *
     * ````
     * function write( $string, $length = NULL ) {
     *
     *     $handler = fopen( './file.txt', 'w' );
     *
     *     if( $length !== NULL ) {
     *
     *         if( (int) $length === 0 ) {
     *
     *             throw new Exception(
     *                 'Using zero-length when writing data, will result in a blank file/empty stream'
     *             );
     *         }
     *
     *         $bytes = fwrite( $handler, $string, $length );
     *
     *     } else {
     *
     *        $bytes = fwrite( $handler, $string );
     *     }
     *
     *     return $bytes;
     * }
     *
     * try {
     *
     *     var_dump( write( 'some text', 0 ) );
     *
     * } catch( Exception $e ) {
     *
     *     echo $e -> getMessage();
     * }
     * ````
     *
     * Because of the WRITE mode (`w`) the code above will output the Exception
     * message, but the file named <strong>file.txt</strong> will already be
     * emptied not because of fwrite(), but because of fopen() already
     * truncated the file
     *
     * By using the "c" or "c+" mode (see below), this does not happen.
     */
    const CONDITIONAL_WRITE = 'cb';

    /**
     * Conditional Read and Write.
     * The same as READ and WRITE (w+), but the file is not truncated nor any
     * error is raised if the file already exists (opposed to EXCLUSIVE)
     *
     * @var string
     */
    const CONDITIONAL_READ_WRITE = 'c+b';

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
     */
    public function __construct( $filename, $mode = self::READ, Context $context = NULL ) {

        $this -> filename = trim( $filename );

        $this -> mode = sprintf( '%sb', strtolower( trim( strtr( $mode, [ 'b' => '' ] ) ) ) );

        $this -> context = ( $context !== NULL ? $context : new SocketContext );

        parent::__construct();
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if chosen opening mode is invalid
     */
    public function verify() {

        if( ! in_array( $this -> mode, [ 'rb', 'r+b', 'wb', 'w+b', 'ab', 'a+b', 'xb', 'x+b', 'cb', 'c+b' ] ) ) {
            throw new InvalidArgumentException( 'Invalid opening mode' );
        }
    }

    // Adapter Interface Methods Implementation

    /**
     * Open a File (or URL)
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown when unable to open the Stream
     */
    public function open() : void {

        try {

            $this -> isOpened();

        } catch( InvalidArgumentException $e ) {

            // Checking Opening Permissions

            $this -> checkPermissions();

            /**
             * @internal
             *
             * We are using the error suppression just because we want
             * ONLY our Exception
             */
            $this -> stream = @fopen(

                $this -> filename, $this -> mode,

                FALSE, $this -> context -> getContext()
            );

            if( $this -> stream === FALSE ) {

                throw new RuntimeException(

                    sprintf(

                        'Unable to open a File/URL Stream to <strong>%s</strong>',

                        $this -> filename
                    )
                );
            }
        }
    }

    /**
     * Close opened Stream
     *
     * @return boolean
     *  TRUE on success and FALSE otherwise
     */
    public function close() : bool {

        try {

            $this -> isOpened();

            return ( fclose( $this -> stream ) !== FALSE );

        } catch( InvalidArgumentException $e ) {

            return FALSE;
        }
    }

    /**
     * Test if Stream has achieved the End of File
     *
     * @return boolean
     *  TRUE if EOF (End-of-File) was achieved -OR- if a
     *  `Next\Exception\Exceptions\InvalidArgumentException` has been caught
     *  because the Stream is not opened — in order to abort any sort of
     *  reading process — and FALSE otherwise
     */
    public function eof() : bool {

        try {
            return ( $this -> isOpened() ? feof( $this -> stream ) : FALSE );
        } catch( InvalidArgumentException $e ) {
            return TRUE;
        }
    }

    /**
     * Tell the current position of Stream Pointer
     *
     * @return integer
     *  Pointer position if it could be retrieved and FALSE otherwise
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if unable to retrieve the current position of Stream Pointer
     *  because the Stream is not valid or because an error occurred while
     *  using fseek()
     */
    public function tell() : int {

        if( ! $this -> valid() || ( $tell = ftell( $this -> stream ) ) === FALSE ) {
            throw new RuntimeException( 'Unable to retrieve current Stream Pointer' );
        }

        return $tell;
    }

    /**
     * Get the size of Stream
     *
     * @return integer
     *  Returns the length of the Stream if it's valid and FALSE otherwise
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if unable to retrieve Stream size because the Stream is not valid
     *
     * @see \Next\HTTP\Stream\Adapter\Adapter::valid()
     */
    public function size() : int {

        if( ! $this -> valid() ) {
            throw new RuntimeException( 'Unable to retrieve Stream size' );
        }

        return strlen( stream_get_contents( $this -> stream ) );
    }

    /**
     * Check if Stream was opened, by testing its Resource
     *
     * @return boolean
     *  Always TRUE, because if Stream is not opened an Exception
     *  will be thrown
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if opened Stream is not a valid Resource.
     */
    public function isOpened() : bool {

        if( ! is_resource( $this -> stream ) ) {

            throw new InvalidArgumentException(

                'Opened Stream is not a valid resource and, therefore,
                can\'t be manipulated'
            );
        }

        return TRUE;
    }

    /**
     * Get Stream Meta Data
     *
     * @return array
     *  Stream Metadata
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if unable to retrieve Stream Metadata because the Stream is
     *  not valid
     */
    public function getMetaData() : array {

        if( ! $this -> valid() ) {
            throw new RuntimeException( 'Unable to retrieve Stream Metadata' );
        }

        return stream_get_meta_data( $this -> stream );
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
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if unable to seek to a Stream position because the Stream is not
     *  valid or because an error occurred while using fseek()
     */
    public function seek( $position ) : void {

        if( ! $this -> valid() || fseek( $this -> stream, $position ) == -1 ) {
            throw new RuntimeException( 'Unable to seek to Stream position' );
        }
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
     *  If Pointer position can be retrieved, it will be returned
     *  Otherwise — or if Stream is not valid — FALSE will
     */
    public function key() {

        if( ! $this -> valid() ) return FALSE;

        try {

            return $this -> tell();

        } catch( RuntimeException $e ) {

            return FALSE;
        }
    }

    /**
     * Move forward to next element, if possible
     *
     * @see Socket::seek()
     * @see Socket::tell()
     */
    public function next() : void {

        try {

            $this -> seek( $this -> tell() );

        } catch( RuntimeException $e ) {
            return;
        }
    }

    /**
     * Rewind the Iterator to the first element
     *
     * <p>In Stream context, moves pointer to beginning of file</p>
     *
     * @return boolean
     *  TRUE if Stream is valid and Pointer can be rewinded and FALSE otherwise
     */
    public function rewind() : void {

        if( ! $this -> valid() ) return;

        rewind( $this -> stream );
    }

    /**
     * Check if current position is valid.
     *
     * This method is called after Iterator::rewind() and Iterator::next()
     * to check if the current position still valid
     *
     * This a "Interface Alias" for Next\HTTP\Stream\Adapter\Adapter::eof()
     *
     * @return boolean
     *  TRUE if EOF (End-of-File) was NOT achieved
     *
     * @see Next\HTTP\Stream\Adapter\Socket::eof()
     */
    public function valid() {
        return ( ! $this -> eof() );
    }

    // Auxiliary Methods

    /**
     * Check if we have permissions to open the Stream
     * in according to given Opening's Mode
     *
     * @return boolean
     *  Always TRUE, because on failure an Exception is thrown
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if Stream is not readable and opening more requires readability
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if Stream is not writable — nor its parent directory — and
     *  opening mode required writability
     */
    private function checkPermissions() : void {

        // Setting Up FileInfo Object

        $info = new \SplFileInfo( $this -> filename );

        switch( $this -> mode ) {

            // Checking Readability

            case self::READ:
            case self::READ_WRITE:

            case self::TRUNCATE_READ_WRITE:
            case self::APPEND_READ_WRITE:

                if( $info -> isFile() && ! $info -> isReadable() ) {

                    throw new RuntimeException(

                        sprintf(

                            'File <strong>%s</strong> is not readable',

                            $this -> filename
                        )
                    );
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

                    throw new RuntimeException(

                        sprintf(

                            'File <strong>%s</strong> is not writeable',

                            $this -> filename
                        )
                    );

                } else {

                    $path = new \SplFileInfo( $info -> getPath() );

                    if( ! $path -> isWritable() ) {

                        throw new RuntimeException(

                            sprintf(

                                'Directory <strong>%s</strong> is not writeable',

                                $path
                            )
                        );
                    }
                }

            break;

            case self::EXCLUSIVE_WRITE:
            case self::EXCLUSIVE_READ_WRITE:

                // File already exists

                if( $info -> isFile() ) {

                    throw new RuntimeException(

                        sprintf(

                            'File <strong>%s</strong> already exists and cannot
                            be opened for exclusive writing',

                            $this -> filename
                        )
                    );

                } else {

                    $path = new \SplFileInfo( $info -> getPath() );

                    if( ! $path -> isWritable() ) {

                        throw new RuntimeException(

                            sprintf(

                                'Directory <strong>%s</strong> is not writeable',

                                $path
                            )
                        );
                    }
                }

            break;
        }
    }
}