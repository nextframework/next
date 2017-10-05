<?php

/**
 * Class Mapper Standard Output Writer (PHP-array) | FileSystem\Prototypes\ClassMapper\Writer\Standard.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\FileSystem\Prototypes\ClassMapper\Writer;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Interfaces\Verifiable;    # Verifiable Interface
use Next\Components\Object;                   # Object Class
use Next\HTTP\Response;                       # Response Class

/**
 * The Standard Output Format writes the generated classmap to a
 * PHP file with the structured organized into a PHP-array, outputs
 * it directly to the browser or returns it as string
 *
 * @package    Next\FileSystem
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException,
 *             Next\Components\Interfaces\Verifiable,
 *             Next\Components\Object,
 *             Next\HTTP\Response
 */
class Standard extends Object implements Verifiable, Writer {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        'filename'          => [ 'required' => FALSE, 'default' => 'map.php' ],
        'save'              => [ 'required' => FALSE, 'default' => FALSE     ],
        'display'           => [ 'required' => FALSE, 'default' => FALSE     ],
        'outputDirectory'   => [ 'required' => FALSE, 'default' => ''        ]
    ];

    // Writer Interface Method Implementation

    /**
     * Classmapper Output Builder
     *
     * @param array $map
     *  Classmap array
     *
     * @return string|void
     *  If the Parameter Option 'save' is set to TRUE, the
     *  PHP-array will will be written down to a file
     *  named accordingly to the Parameter Option 'filename' and
     *  nothing is returned
     *  If the Parameter Option 'display' is set to TRUE instead, the
     *  structure will be sent directly to the browser.
     *  If both are set to FALSE, then the content will be returned
     *  as string
     */
    public function build( array $map ) {

        if( $this -> options -> save !== FALSE ) {

            file_put_contents(

                sprintf(

                    '%s/%s',

                    $this -> options -> outputDirectory, $this -> options -> filename
                ),

                sprintf(

                    "<?php\n\n\$map = %s;",

                    str_replace( '\\\\', '\\', var_export( $map, TRUE ) )
                )
            );

            return;
        }

        if( $this -> options -> display !== FALSE ) {
            print '<pre>'; var_export( $map ); return;
        }

        return $map;
    }

    // Verifiable Interface Method Implementation

    /**
     * Verify Object Integrity
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if defined to output the PHP-array to a file but the
     *  output directory is empty, it's not a valid directory or it's
     *  not writeable
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if defined to output the PHP-array to a file but the
     *  filename is empty
     */
    public function verify() {

        if( $this -> options -> save ) {

            if( empty( $this -> options -> outputDirectory ) ||
                    ! is_dir( $this -> options -> outputDirectory ) ||
                        ! is_writable( $this -> options -> outputDirectory ) ) {

                throw new InvalidArgumentException(

                    'In order to output the Classmap to a PHP-array
                    in a file a non-empty writeable Output Directory
                    must be provided'
                );
            }

            if( empty( $this -> options -> filename ) ) {

                throw new InvalidArgumentException(
                    'In order to output the Classmap to a PHP-array
                    in a file a filename must be provided'
                );
            }
        }
    }
}