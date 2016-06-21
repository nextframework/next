<?php

namespace Next\Tools\Routes\Generators\Writer;

use Next\HTTP\Stream\Writer;
use Next\HTTP\Stream\Adapter\Socket;
use Next\HTTP\Stream\Adapter\AdapterException;    # Stream Adapter Exception
use Next\HTTP\Stream\Writer\WriterException;      # HTTP Stream Writer Exception Class

/**
 * Routes Generator Tool: Standard Output Writer (PHP arrays)
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Standard extends AbstractWriter {

    /**
     * Integrity Check
     *
     * @throws Next\Tools\Routes\Generators\Writer\WriterException
     *  Throw if the required <strong>filePath</strong> option with
     *  the path to the PHP file to write the array is missing or empty
     */
    protected function checkIntegrity() {

        if( ! isset( $this -> options -> filePath ) ) {
            throw WriterException::missingConfigurationOption( 'filePath' );
        }
    }

    // Output Writer Interface Methods

    /**
     * Saves found Routes to be used by Router Classes
     *
     * @param array $data
     *  Data to be written
     *
     * @return integer
     *  Number of records processed
     *
     * @throws Next\Tools\Routes\Generators\Writer\WriterException
     *  Unable to record route, as a rethrowing of a
     *  Next\DB\Statement\StatementException caught
     */
    public function save( array $data ) {

        set_time_limit( 0 );

        $structure = array();

        foreach( $data as $application => $controllersData ) {

            foreach( $controllersData as $controller => $actionsData ) {

                foreach( $actionsData as $action => $data ) {

                    foreach( $data as $d ) {

                        $structure[] = array(

                            'requestMethod'    => $d['requestMethod'],
                            'application'      => $application,
                            'class'            => $controller,
                            'method'           => $action,
                            'URI'              => $d['route'],
                            'requiredParams'   => serialize( $d['params']['required'] ),
                            'optionalParams'   => serialize( $d['params']['optional'] )
                        );
                    }
                }
            }
        }

        $records = count( $structure );

        // Writing File

        try {

            $writer = new Writer(
                new Socket( $this -> options -> filePath, Socket::READ_WRITE )
            );

            clearstatcache();

            $filesize = (int) @filesize( $this -> options -> filePath );

            if( $filesize > 0 ) {

                /**
                 * @internal
                 * If current PHP file is not empty, let's include and
                 * merge its content with current structure before write it down
                 */
                include $this -> options -> filePath;

                $structure = array_merge( $structure, ( isset( $routes ) ? $routes : array() ) );
            }

            $writer -> write(
                "<?php\n\n\$routes = " . var_export( $structure, TRUE ) . ';'
            );

            $writer -> getAdapter() -> close();

        } catch( WriterException $e ) {

            // Re-throw as WriterException

            throw OutputWriterException::recordingFailure(

                array( $d['route'], $controller, $action, $e -> getMessage() )
            );

        } catch( AdapterException $e ) {

            // Re-throw as WriterException

            throw OutputWriterException::recordingFailure(

                array( $d['route'], $controller, $action, $e -> getMessage() )
            );
        }

        return $records;
    }

    /**
     * Resets the Writer to be used again
     */
    public function reset() {

        $writer = new Writer(
            new Socket( $this -> options -> filePath, Socket::TRUNCATE_WRITE )
        );

        /**
         * @internal
         * We don't need to write anything, but Next\HTTP\Stream\Writer\Writer::write()
         * must be called so the 'wb' opening mode used in Next\HTTP\Stream\Adapter\Socket
         * constructor above can do its job emptying the file
         */
        $writer -> write( '' );

        $writer -> getAdapter() -> close();
    }
}