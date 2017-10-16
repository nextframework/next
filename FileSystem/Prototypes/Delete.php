<?php

/**
 * File/Directory Removal Prototype Class | FileSystem\Prototypes\Delete.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\FileSystem\Prototypes;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface

/**
 * Removes a File/Directory.
 * If removing a Directory, operates recursively, with file filters
 * and parent directory destruction if empty
 *
 * @package    Next\FileSystem
 */
class Delete implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the Directory/File Removal routine by proxying,
     * treating and handling the mixed arguments received
     *
     * @return boolean
     *  Returns TRUE if file(s) could be removed and FALSE otherwise
     */
    public function prototype() {

        list( $path, $files, $destroy ) = func_get_arg( 0 ) + [ NULL, [], FALSE ];

        return $this -> delete( $path, $files, $destroy );
    }

    /**
     * Cleans and inverts slashes of given path
     *
     * ````
     * $path =  new Path( [ 'value' => 'path/to/delete/files' ] );
     *
     * // Delete all files but keep given directory empty
     *
     * $path -> delete();
     *
     * // Delete 'file1.txt' and 'file2.txt' from given directory if exists
     *
     * $path -> delete( [ 'file1.txt', 'file2.txt' ] );
     *
     * // Delete 'file1.txt' and 'file2.txt', if exists, from and inside 'TXT' subdirectory if exists
     *
     * $path -> delete( [ 'TXT' => [ 'file1.txt', 'file2.txt' ] ] );
     *
     * // Delete all files and remove empty directory
     *
     * $path -> delete( [], TRUE );
     *
     * ````
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
    private function delete( $path, array $files = [], $destroy = FALSE ) {

        if( $path === NULL || ( $path = stream_resolve_include_path( $path ) ) === FALSE ) return TRUE;

        /**
         * @internal
         *
         * We could use SplFileInfo:isDir() and SplFileInfo::isLink()
         * but seems to be a bad idea create an object just for this
         */
        if( ! is_dir( $path ) || is_link( $path ) ) {

            //chmod( $path, 0777 );

            return unlink( $path );
        }

        //-----------------------------

        // Listing files...

        $items = scandir( $path );

        // Iterating...

        foreach( $items as $index => $item ) {

            // Ignoring '.' and '..'

            if( $item == '.' || $item == '..' ) continue;

            /**
             * @internal
             *
             * If we don't have a specific list of files to be deleted,
             * all of them will
             */
            if( count( $files ) == 0 ) {

                // Trying to remove the File...

                if( ! $this -> delete( sprintf( '%s/%s', $path, $item ), $files, $destroy ) ) {
                    return FALSE;
                }

               /**
                * @internal
                *
                * File deleted successfully, let's remove it
                * from Directory's Files Listing too
                */
               unset( $items[ $index ] );

            } else {

                foreach( $files as $subdir => $f ) {

                    /**
                     * @internal
                     *
                     * If we have an array of Files to be deleted,
                     * but we don't have them as sub-array...
                     */
                    if( (array) $f !== $f ) {

                        /**
                         * @internal
                         *
                         * ... we just need to check if the current
                         * file is on the list so we can TRY to remove it
                         */
                        if( in_array( $item, $files ) ) {

                            if( ! $this -> delete( sprintf( '%s/%s', $path, $item ), $files, $destroy ) ) {
                                return FALSE;
                            }

                            /**
                            * @internal
                            *
                            * File deleted successfully, let's remove it
                            * from Directory's Files Listing too
                            */
                           unset( $items[ $index ] );
                        }

                    } else {

                       /**
                        * @internal
                        *
                        * Now if we do have a list, with subpath as
                        * index and an array of files as value,
                        * we'll build another path to execute the deletion
                        *
                        * This new path (also called "subpath") is built
                        * with the index of current iteration followed
                        * by the current path from initial iteration
                        */
                        return $this -> delete(
                            sprintf( '%s/%s', $path, $subdir ), $f, $destroy
                        );
                    }
                }
            }
        }

        // Should we remove empty directories?

        if( $destroy && count( $items ) == 0 ) {
            return rmdir( $path );
        }

        return TRUE;
    }
}