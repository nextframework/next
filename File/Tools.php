<?php

namespace Next\File;

/**
 * File Tools Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Tools {

    /**
     * Clean path and invert slashes
     *
     * <p>
     *     Wrapper method for clean boundaries slashes from given path
     *     and invert the position of backslashes
     * </p>
     *
     * <p><strong>Usage:</strong></p>
     *
     * <code>
     *  Tools::cleanAndInvertPath( '   path\with\backslashes' ) // path/with/backslashes (without left spaces)
     * </code>
     *
     * @param string $path
     *  Path to work with
     *
     * @return string
     *  Cleaned and fixed path
     */
    public static function cleanAndInvertPath( $path ) {

        // Clean boundary spaces

        $path = trim( $path );

        // Remove trailing slash

        $path = rtrim( $path, '/\\' );

        return str_replace( '\\', '/', $path );
    }

    /**
     * Quotes a string, by wrapping it with given identifier
     *
     * @param  string $string
     *  String to quote
     *
     * @param  string $identifier
     *  Quote identifier
     *
     * @return string
     *  Input string, quoted
     */
    public static function quote( $string, $identifier = '"' ) {
        return sprintf( '%s%s%s', $identifier, $string, $identifier );
    }

    /**
     * Formats given file size to be more human readable, by converting bytes
     * to greater units and adding the proper acronym
     *
     * @param  integer $size
     *   File szie to format
     *
     * @return string
     *  File size formatted
     */
    public static function readableFilesize( $size ) {

        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor( log( $size, 1024 ) ) : 0;

        return number_format( $size / pow( 1024, $power ), 2, '.', ',' ) . ' ' . $units[ $power ];
    }

    /**
     * Get file extension
     *
     * @param  string $file
     *  File to retrieve extension
     *
     * @return string|NULL
     *  - File extension, if given file has at least one
     *  - An empty string, if given file has a malformed extension
     *  - NULL if given file has no extension
     */
    public static function getFileExtension( $file ) {
        return pathinfo( $file, PATHINFO_EXTENSION );
    }

    /**
     * Delete file(s) and/or folder(s)
     *
     * <p>
     *     Delete file(s) (if specified) from given directory recursively
     *     and allow the directory itself to be removed too.
     * </p>
     *
     * <strong>Usage:</strong>
     *
     * <code>
     *   // Delete all files but keep given directory empty
     *
     *  Tools::delete( 'path/to/delete/files' )
     *
     *   // Delete 'file1.txt' and 'file2.txt' from given directory if exists
     *
     *  Tools::delete( 'path/to/delete/files', array( 'file1.txt', 'file2.txt' ) )
     *
     *   // Delete 'file1.txt' and 'file2.txt', if exists, from and inside 'TXT' subdirectory yif exists
     *
     *  Tools::delete( 'path/to/delete/files', array( 'TXT' => array( 'file1.txt', 'file2.txt' ) ) )
     *
     *   // Delete all files and remove empty directory
     *
     *  Tools::delete( 'path/to/delete/files', array, TRUE )
     * </code>
     *
     * @param string $path
     *  Directory to work with
     *
     * @param array|optional $files
     *  A list of files to be deleted. If empty, all files found will
     *
     * @param boolean|optional $destroy
     *  Flag conditioning is given directory should be destroyed if empty,
     *  after the process
     *
     * @return boolean
     *  TRUE on success and FALSE otherwise
     */
    public static function delete( $path, array $files = array(), $destroy = FALSE ) {

        if( ! file_exists( $path ) ) {
            return TRUE;
        }

        /**
         * @internal
         * We could use SplFileInfo:isDir() and SplFileInfo::isLink()
         * but seems to be a bad idea create an object just for this
         */
        if( ! is_dir( $path ) || is_link( $path ) ) {

            // Set full permission to target file...

            chmod( $path, 0777 );

            // ... and trying to remove it

            return unlink( $path );
        }

        //-----------------------------

        // Listing files...

        $items = scandir( $path );

        // Shifting '.' and '..' entries (first two indexes)

        array_shift( $items ); array_shift( $items );

        // Iterating...

        foreach( $items as $index => $item ) {

            // If we don't have a specific list of files to be deleted, all them will

            if( count( $files ) == 0 ) {

                // Trying to remove the File...

                if( ! self::delete( sprintf( '%s/%s', $path, $item ), $files, $destroy ) ) {

                    // ... FAIL! xD

                    return FALSE;

                } else {

                   // File deleted successfully. Let's remove it from Directory's Files Listing too

                   unset( $items[ $index ] );
                }

            } else {

                foreach( $files as $subdir => $f ) {

                    /**
                     * @internal
                     * If we have an array of Files to be Deleted,
                     * but we don't have them as sub-array...
                     */
                    if( ! is_array( $f ) ) {

                        // ... we just need to check if the current file is the list...

                        if( in_array( $item, $files ) ) {

                            // ... so we can TRY to remove it

                            if( ! self::delete( sprintf( '%s/%s', $path, $item ), $files, $destroy ) ) {

                                // FAIL! xD

                                return FALSE;

                            } else {

                                // File deleted successfully. Let's remove it from Directory's Files Listing

                                unset( $items [ $index ] );
                            }
                        }

                    } else {

                       /**
                        * @internal
                        * Otherwise, if we have a list, with subpath as index and
                        * an array of files as value, we'll build another path to
                        * execute the deletion.
                        *
                        * This new path (also called "subpath") is built with
                        * the index of current iteration followed by the current path
                        * from initial iteration.
                        */
                        return self::delete( sprintf( '%s/%s', $path, $subdir ), $f, $destroy );
                    }
                }
            }
        }

        // If the directory is empty, should we remove it?

        if( $destroy && count( $items ) == 0 ) {
            return rmdir( $path );
        }

        return TRUE;
    }
}
