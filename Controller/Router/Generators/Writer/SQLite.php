<?php

/**
 * Routes Generator SQLite Output Writer Class | Controller\Router\Generators\Writer\SQLite.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller\Router\Generators\Writer;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Interfaces\Verifiable;           # Verifiable Interface
use Next\Components\Object;                          # Object Class
use Next\DB\Driver\PDO\Adapter\SQLite as Adapter;    # PDO SQLite Adapter Class
use Next\DB\Entity\Manager;                          # Entity Manager Class
use Next\DB\Entity\Repository;                       # Repository Class

/**
 * Routes Generator Tool: SQLite Output Writer
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 *
 * @uses          Next\Components\Object,
 *                Next\DB\Driver\PDO\Adapter\SQLite,
 *                Next\DB\Entity\Manager
 *                Next\DB\Entity\Repository
 *                Next\Tools\Routes\Generators\Writer\SQLite\Entity,
 */
class SQLite extends Object implements Verifiable, Writer {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'dbPath' => [ 'required' => FALSE, 'default' => __DIR__ . '/SQLite/routes.sqlite' ]
    ];

    /**
     * Routes' Entity Manager
     *
     * @var \Next\DB\Entity\Manager $routes
     */
    protected $routes;

    /**
     * Additional Initialization.
     * Configures the Entity Manager with the Entity Repository
     * used to record found Routes
     */
    protected function init() {

        // Routes' Entity Manager

        $this -> routes = new Manager(
            [
              'driver'     =>    new Adapter( [ 'dbPath' => $this -> options -> dbPath ] ),
              'repository' => new Repository( [ 'entity' => new SQLite\Entity ] )
            ]
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
     */
    public function save( array $data ) {

        set_time_limit( 0 );

        $records = 0;

        foreach( $data as $application => $controllers ) {

            foreach( $controllers as $controller => $actions ) {

                foreach( $actions as $method => $data ) {

                    foreach( $data as $d ) {

                        $this -> routes -> flush();

                        // Building Route Entity

                        $entity = $this -> routes -> getEntity();

                        $entity -> requestMethod  = $d['requestMethod'];
                        $entity -> application    = $application;
                        $entity -> controller     = $controller;
                        $entity -> method         = $method;
                        $entity -> URI            = $d['route'];
                        $entity -> requiredParams = serialize( $d['params']['required'] );
                        $entity -> optionalParams = serialize( $d['params']['optional'] );

                        /**
                         * @internal
                         *
                         * Modifying initially provided Entity
                         * and inserting
                         */
                        $this -> routes
                              -> setEntity( $entity )
                              -> insert();

                        // Increment Counter

                        $records += 1;
                    }
                }
            }
        }

        return $records;
    }

    /**
     * Empties the SQLite Database before record found Routes
     */
    public function reset() {
        $this -> routes -> delete() -> rowCount();
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'dbPath' has been overwritten with
     *  a not resolvable path
     */
    public function verify() {

        if( stream_resolve_include_path( $this -> options -> dbPath ) === FALSE ) {

            throw new InvalidArgumentException(

                sprintf(

                    'Argument <strong>%s</strong> is not a valid filepath',

                    ( $this -> options -> dbPath !== NULL ? $this -> options -> dbPath : 'NULL' )
                )
            );
        }
    }
}