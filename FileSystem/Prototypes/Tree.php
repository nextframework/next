<?php

/**
 * Directory File Tree Prototype Class | FileSystem\Prototypes\Tree.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\FileSystem\Prototypes;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\RuntimeException;
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface

/**
 * Displays the contents of a given directory recursively in a
 * hierarchical tree format
 *
 * @package    Next\FileSystem
 *
 * @uses       Next\Exception\Exceptions\RuntimeException
 *             Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Interfaces\Prototypable
 *             RecursiveTreeIterator
 *             RecursiveDirectoryIterator
 *             RecursiveIteratorIterator
 *             UnexpectedValueException
 */
class Tree implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the Hierarchical Tree display routine by proxying,
     * treating and handling the mixed arguments received
     *
     * @see Tree::tree();
     */
    public function prototype() : void {

        list( $directory ) = func_get_arg( 0 ) + [ NULL ];

        $this -> tree( $directory ); return;
    }

    /**
     * Traverses through the directories of provided directory and
     * display them in a hierarchical directory tree format
     *
     * @param string $directory
     *  Directory to work with
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if informed directory is not resolvable (i.e. doesn't exist)
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Throw if a native \UnexpectedValueException is caught
     */
    private function tree( string $directory ) : void {

        if( stream_resolve_include_path( $directory ) === FALSE ) {

            throw new InvalidArgumentException(
                sprintf( 'Directory <strong>%s</strong> cannot be traversed', $directory )
            );
        }

        try {

            $iterator = new \RecursiveTreeIterator(
                            new \RecursiveDirectoryIterator(
                                $directory, \RecursiveDirectoryIterator::SKIP_DOTS
                            ),

                            \RecursiveIteratorIterator::SELF_FIRST
                        );

            print '<pre>';

            foreach( $iterator as $path ) {
                echo strtr( $path, [ $directory => '' ] ), "\n";
            }

        } catch( \UnexpectedValueException $e ) {

            throw new RuntimeException(

                sprintf(
                    'Directory <strong>%s,</strong> cannot be traversed', $directory
                ),

                RuntimeException::PHP_ERROR
            );
        }
    }
}