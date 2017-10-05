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

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface

/**
 * Display the contents of a given directory recursively in a
 * hierarchical tree format
 *
 * @package    Next\FileSystem
 */
class Tree implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the Hierarchical Tree display routine by proxying,
     * treating and handling the mixed arguments received
     */
    public function prototype() {

        list( $directory ) = func_get_arg( 0 ) + [ NULL ];

        return $this -> tree( $directory );
    }

    /**
     * Traverses through the directories of provided directory and
     * display them in a hierarchical directory tree format
     *
     * @param string $directory
     *  Directory to work with
     */
    private function tree( $directory ) {

        if( $directory === NULL ||
                ( $directory = stream_resolve_include_path( $directory ) ) === FALSE ) {

            return;
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
                echo str_replace( $directory, '', $path ), "\n";
            }

        } catch( \UnexpectedValueException $e ) {

            throw new RuntimeException(
                sprintf( 'Directory <strong>%s,</strong> cannot be traversed', $directory )
            );
        }
    }
}