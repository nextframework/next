<?php

/**
 * Routes Generator SQLite Output Writer Class | Tools\Routes\Generators\Writer\SQLite.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Tools\Routes\Generators\Writer;

use Next\Tools\Routes\Generators\Writer\OutputWriterException;    # Output Writer Exception Class
use Next\DB\Table\TableException;                                 # Table Exception Class

use Next\Components\Object;                                       # Object Class

use Next\DB\Driver\PDO\Adapter\SQLite as Adapter;                 # PDO SQLite Adapter
use Next\DB\Table\Manager;                                        # Table Manager

/**
 * Routes Generator Tool: SQLite Output Writer
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 *
 * @uses          \Next\DB\Driver\PDO\Adapter\SQLite,
 *                \Next\Tools\Routes\Generators\Writer\SQLite\Entity,
 *                \Next\Tools\Routes\Generators\Writer\OutputWriterException
 *                \Next\DB\Table\TableException,
 *                \Next\DB\Table\Manager
 */
class SQLite extends Object implements Writer {

    /**
     * Entity Manager
     *
     * @var \Next\DB\Table\Manager $manager
     */
    protected $manager;

    /**
     * Additional Initialization
     *
     * Creates and configures the Table Manager object needed to
     * connect to the SQLite Database
     *
     * @throws \Next\Tools\Routes\Generators\Writer\OutputWriterException
     *  Thrown if the required <strong>dbPath</strong> option with
     *  the path to the SQLite database file is missing or empty
     */
    protected function init() {

        if( ! isset( $this -> options -> dbPath ) ) {
            throw OutputWriterException::missingConfigurationOption( 'dbPath' );
        }

        // Entity Manager

        $this -> manager = new Manager(
            new Adapter( array( 'dbPath' => $this -> options -> dbPath ) )
        );
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
     * @throws \Next\Tools\Routes\Generators\Writer\OutputWriterException
     *  Unable to record route, as a re-throwing of a
     *  \Next\DB\Statement\StatementException caught
     */
    public function save( array $data ) {

        set_time_limit( 0 );

        $records = 0;

        foreach( $data as $application => $controllersData ) {

            foreach( $controllersData as $controller => $actionsData ) {

                foreach( $actionsData as $method => $data ) {

                    foreach( $data as $d ) {

                        try {

                            // Building Route Entity

                            $entity = new SQLite\Entity;

                            $entity -> requestMethod  = $d['requestMethod'];
                            $entity -> application    = $application;
                            $entity -> controller     = $controller;
                            $entity -> method         = $method;
                            $entity -> URI            = $d['route'];
                            $entity -> requiredParams = serialize( $d['params']['required'] );
                            $entity -> optionalParams = serialize( $d['params']['optional'] );

                            // Recording

                            $this -> manager -> setTable( $entity ) -> insert();

                            // Increment Counter

                            $records += 1;

                        } catch( TableException $e ) {

                            // Re-throw as RoutesDatabaseException

                            throw OutputWriterException::recordingFailure(

                                array( $d['route'], $controller, $method, $e -> getMessage() )
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
     */
    public function reset() {
        $this -> manager -> setTable( new SQLite\Entity ) -> delete() -> rowCount();
    }
}