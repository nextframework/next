<?php

namespace Next\Tools\Routes\Generators\Writer;

use Next\DB\Statement\StatementException;      # SQL Statement Exception Class
use Next\Tools\Routes\RoutesException;         # Routes Generator Exception Class

use Next\DB\Table\Manager;                     # Table Manager

/**
 * Routes Generator Tool: SQLite Output Writer
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class SQLite extends AbstractWriter {

    /**
     * Table Manager Object
     *
     * @var Next\DB\Table\Manager $manager
     */
    private $manager;

    /**
     * Additional Initialization
     *
     * Creates and configures the Table Manager object needed to
     * connect to the SQLite Database
     */
    protected function init() {

        $this -> manager = new Manager(

            new \Next\DB\Driver\PDO\Adapter\SQLite(

                array( 'dbPath' => $this -> options -> dbPath )
            ),

            new SQLite\Table
        );
    }

    /**
     * Integrity Check
     *
     * @throws Next\Tools\Routes\Generators\Writer\OutputWriterException
     *  Throw if the required <strong>dbPath</strong> option with
     *  the path to the SQLite database file is missing or empty
     */
    protected function checkRequirements() {

        if( ! isset( $this -> options -> dbPath ) ) {
            throw OutputWriterException::missingConfigurationOption( 'dbPath' );
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
     * @throws Next\Tools\Routes\Generators\Writer\OutputWriterException
     *  Unable to record route, as a rethrowing of a
     *  Next\DB\Statement\StatementException caught
     */
    public function save( array $data ) {

        set_time_limit( 0 );

        $records = 0;

        foreach( $data as $application => $controllersData ) {

            foreach( $controllersData as $controller => $actionsData ) {

                foreach( $actionsData as $action => $data ) {

                    foreach( $data as $d ) {

                        // Cleaning Information of Previous Iteration

                        $this -> manager -> reset();

                        // Adding new

                        $this -> manager -> setSource(

                            array(

                                'requestMethod'    => $d['requestMethod'],
                                'application'      => $application,
                                'class'            => $controller,
                                'method'           => $action,
                                'URI'              => $d['route'],
                                'requiredParams'   => serialize( $d['params']['required'] ),
                                'optionalParams'   => serialize( $d['params']['optional'] )
                            )
                        );

                        // Inserting...

                        try {

                            $this -> manager -> insert();

                            // Increment Counter

                            $records += 1;

                        } catch( StatementException $e ) {

                            // Re-throw as RoutesDatabaseException

                            throw OutputWriterException::recordingFailure(

                                array( $d['route'], $controller, $action, $e -> getMessage() )
                            );
                        }
                    }
                }
            }
        }

        return $records;
    }

    /**
     * Resets the Writer to be used again
     *
     * @return void
     */
    public function reset() {
        $this -> manager -> delete() -> rowCount();
    }
}