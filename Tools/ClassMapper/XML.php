<?php

/**
 * Class Mapper: XML Format | Tools\ClassMapper\XML.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Tools\ClassMapper;

use Next\Tools\ClassMapper\ClassMapperException;    # ClassMapper Exception Class

use Next\Components\Object;                         # Object Class
use Next\HTTP\Response;                             # Response Class
use Next\XML\Writer;                                # XML Writer Class

/**
 * Class Mapper Tool: XML Output
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XML extends Object implements Mapper {

    /**
     * Default Options
     *
     * @var array $defaultOptions
     */
    protected $defaultOptions = [

        'rootNode'          => 'map',
        'filename'          => 'map.xml',
        'save'              => FALSE,
        'outputDirectory'   => ''
    ];

    /**
     * XML Writer Object
     *
     * @var \Next\XML\Writer $writer
     */
    private $writer;

    /**
     * Additional Initialization
     *
     * Check Options Integrity, initializes the \Next\XML\Writer Object and starts
     * the root node of the XML structure
     *
     * @see \Next\Tools\ClassMapper\XML::checkIntegrity()
     */
    protected function init() {

        $this -> checkIntegrity();

        // Starting XML File

        $this -> writer = new Writer( $this -> options );

        // Adding top Level Parent Node

        $this -> writer -> addParent( $this -> options -> rootNode );
    }

    /**
     * XML Content Builder
     *
     * @param array $map
     *  Mapped Array
     *
     * @return string
     *  If <strng>save</strong> option is set to FALSE, the
     *  XML Output Memory will be returned as string
     */
    public function build( array $map ) {

        // Iterating through Map Data

        $this -> writer -> addParent( 'classes' );

        foreach( $map as $class => $path ) {

            $this -> writer -> addChild(
                'class', NULL, [ 'name' => $class, 'path' => $path ]
            );
        }

        // Displaying...

        if( ! $this -> options -> save ) {

            return $this -> writer -> output( new Response );

        // ... or saving the File

        } else {

            file_put_contents(

                $this -> options -> outputDirectory . $this -> options -> filename,

                $this -> writer -> output( NULL )
            );
        }
    }

    // Auxiliary Methods

    /**
     * Checks Options Integrity
     *
     * @throws \Next\Tools\ClassMapper\ClassMapperException
     *  Output directory is not set
     *
     * @throws \Next\Tools\ClassMapper\ClassMapperException
     *  Output directory doesn't exist or it's not a valid directory
     *
     * @throws \Next\Tools\ClassMapper\ClassMapperException
     *  Output directory is not writable
     *
     * @throws \Next\Tools\ClassMapper\ClassMapperException
     *  Filename is missing
     */
    private function checkIntegrity() {

        // If the root node name was overwritten with an empty string...

        if( empty( $this -> options -> rootNode ) ) {

            throw new ClassMapperException(
                'You must enter the the Top Level Node Name of XML File!'
            );
        }

        // If we want to save the File...

        if( $this -> options -> save ) {

            // ... an Output Directory must be set...

            if( empty( $this -> options -> outputDirectory ) ) {
                throw ClassMapperException::noOutputDirectory();
            }

            // ... must exist

            if( ! is_dir( $this -> options -> outputDirectory ) ) {
                throw ClassMapperException::invalidOutputDirectory( $this -> options -> outputDirectory );
            }

            // ... and must be writable

            if( ! is_writable( $this -> options -> outputDirectory ) ) {
                throw ClassMapperException::unwritableOutputDirectory( $this -> options -> outputDirectory );
            }

            if( $this -> options -> save ) {

                if( empty( $this -> options -> filename ) ) {
                    throw ClassMapperException::missingOutputFilename();
                }
            }
        }
    }
}