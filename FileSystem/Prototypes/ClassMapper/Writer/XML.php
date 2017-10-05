<?php

/**
 * Class Mapper XML Output Writer | FileSystem\Prototypes\ClassMapper\Writer\XML.php
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
 * The XML Output Format writes the generated classmap to a XML file
 * or outputs it directly to the browser
 *
 * @package    Next\FileSystem
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException,
 *             Next\Components\Interfaces\Verifiable,
 *             Next\Components\Object,
 *             Next\HTTP\Response
 */
class XML extends Object implements Verifiable, Writer {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        'root'              => [ 'required' => FALSE, 'default' => 'map'     ],
        'filename'          => [ 'required' => FALSE, 'default' => 'map.xml' ],
        'save'              => [ 'required' => FALSE, 'default' => FALSE     ],
        'outputDirectory'   => [ 'required' => FALSE, 'default' => ''        ]
    ];

    /**
     * XML Writer Object
     *
     * @var \Next\XML\Writer $writer
     */
    private $writer;

    /**
     * Additional Initialization.
     * Initializes the \Next\XML\Writer Object and starts the root
     * node of the XML structure
     */
    protected function init() {

        // Starting XML File

        $this -> writer = new \Next\XML\Writer( $this -> options );

        // Adding top Level Parent Node

        $this -> writer -> addParent( $this -> options -> root );
    }

    // Writer Interface Method Implementation

    /**
     * Classmapper Output Builder
     *
     * @param array $map
     *  Classmap array
     *
     * @return string|void
     *  If the Parameter Option 'save' is set to FALSE, the
     *  XML Output Memory will be returned as string.
     *  Otherwise the XML memory will flushed to a file named
     *  accordingly to the Parameter Option 'filename' and
     *  nothing is returned
     */
    public function build( array $map ) {

        $this -> writer -> addParent( 'classes' );

        foreach( $map as $class => $path ) {

            $this -> writer -> addChild(
                'class', NULL, [ 'name' => $class, 'path' => $path ]
            );
        }

        if( ! $this -> options -> save ) {
            return $this -> writer -> output( new Response );
        }

        file_put_contents(

            $this -> options -> outputDirectory . $this -> options -> filename,

            $this -> writer -> output( NULL )
        );
    }

    // Verifiable Interface Method Implementation

    /**
     * Verify Object Integrity
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if top-level node name is empty
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if defined to output the XML memory to a file but
     *  the output directory is empty, it's not a valid directory or
     *  it's not writeable
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if defined to output the XML memory to a file but
     *  the filename is empty
     */
    public function verify() {

        if( empty( $this -> options -> root ) ) {

            throw new InvalidArgumentException(
                'Missing top-level node name'
            );
        }

        if( $this -> options -> save ) {

            if( empty( $this -> options -> outputDirectory ) ||
                    ! is_dir( $this -> options -> outputDirectory ) ||
                        ! is_writable( $this -> options -> outputDirectory ) ) {

                throw new InvalidArgumentException(

                    'In order to output the Classmap to a XML File a
                    non-empty writeable Output Directory must be provided'
                );
            }

            if( empty( $this -> options -> filename ) ) {

                throw new InvalidArgumentException(
                    'In order to output the Classmap to a XML File a
                    filename must be provided'
                );
            }
        }
    }
}