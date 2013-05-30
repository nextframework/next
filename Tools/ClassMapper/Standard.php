<?php

namespace Next\Tools\ClassMapper;

use Next\Tools\ClassMapper\ClassMapperException;    # ClassMapper Exception Class

/**
 * Class Mapper Tool: Standard Format (Array)
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Standard extends AbstractMapper {

    /**
     * PHP Array Builder
     *
     * @param array $map
     *   Mapped Array
     */
    public function build( array $map ) {

        // Displaying...

        if( ! $this -> options -> save ) {

            print '<pre>'; print_r( $map );

        // ... or saving the File

        } else {

            $content = sprintf(

                "<?php\n\n\$map = %s;\n\nreturn \$map;\n\n?>",

                str_replace( '\\\\', '\\', var_export( $map, TRUE ) )
            );


            file_put_contents(

                $this -> options -> outputDirectory . '/' .
                    $this -> options -> filename, $content
            );
        }
    }

    // Parameterizable Interface Method Implementation

    /**
     * Setup Standard (PHP-Array) ClassMapper Options
     *
     * @return array
     *   Standard (PHP-Array) ClassMapper Options
     */
    public function setOptions() {

        return array(

            'filename'          => 'map.php',
            'save'              => FALSE,
            'outputDirectory'   => ''
        );
    }

    /**
     * Check Options Integrity
     *
     * @throws Next\Tools\ClassMapper\ClassMapperException
     *   Output directory is not set
     *
     * @throws Next\Tools\ClassMapper\ClassMapperException
     *   Output directory doesn't exist or it's not a valid directory
     *
     * @throws Next\Tools\ClassMapper\ClassMapperException
     *   Output directory is not writable
     *
     * @throws Next\Tools\ClassMapper\ClassMapperException
     *   Filename is missing
     */
    protected function checkIntegrity() {

        // If we want to save the File...

        if( $this -> options -> save !== FALSE ) {

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

            // ... and a filename must be set too

            if( empty( $this -> options -> filename ) ) {
                throw ClassMapperException::missingOutputFilename();
            }
        }
    }
}