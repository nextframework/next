<?php

/**
 * Class Mapper Prototype Class | FileSystem\Prototypes\ClassMapper.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\FileSystem\Prototypes;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface

/**
 * Traverses through a directory searching for all PHP Classes and
 * builds a list exportable to different formats.
 *
 * The main usefulness of this Prototype is create AutoLoading Map Files
 *
 * @package    Next\FileSystem
 */
class ClassMapper implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes theClass Mapper routine by proxying,
     * treating and handling the mixed arguments received
     *
     * @return mixed
     *  Returns what the Class Mapper Writer returns, which could be
     *  nothing (i.e. outputs to browser, writes a file...) or maybe
     *  a string to be copied
     */
    public function prototype() {

        list( $path, $writer ) = func_get_arg( 0 ) + [ NULL, new ClassMapper\Writer\XML ];

        return $writer -> build( $this -> map( $path ) );
    }

    /**
     * Traverses through the directories of provided path searching
     * for PHP classes, parsing their tokens and building an array
     * with their paths
     *
     * @param string $path
     *  Directory to work with
     *
     * @return array
     *  An array with a map of classes and their paths or an empty
     *  array if no path is provided or a non-resolvable is
     */
    private function map( $path ) {

        if( $path === NULL || ( $path = stream_resolve_include_path( $path ) ) === FALSE ) return [];

        $iterator = new ClassMapper\ClassMapper(
                        new \RecursiveIteratorIterator(
                            new \RecursiveDirectoryIterator( $path )
                        )
                    );

        $map = [];

        iterator_apply(

            $iterator,

            function( $iterator, &$map ) {

                $file = $iterator -> current();

                $namespace = '';

                if( ! empty( $file -> namespace ) ) {
                    $namespace = sprintf( '%s\\', $file -> namespace );
                }

                $classname = sprintf( '%s%s', $namespace, $file -> classname );

                $map[ $classname ] = $file -> getRealPath();

                return TRUE;
            },

            [ $iterator, &$map ]
        );

        return $map;
    }
}