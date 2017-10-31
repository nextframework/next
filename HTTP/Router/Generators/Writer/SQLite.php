<?php

/**
 * Routes Generator SQLite Output Writer Class | HTTP\Router\Generators\Writer\SQLite.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Router\Generators\Writer;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Validation\Verifiable;                      # Verifiable Interface
use Next\Components\Object;                          # Object Class
use Next\DB\Driver\PDO\Adapter\SQLite as Adapter;    # PDO SQLite Adapter Class
use Next\DB\Entity\Manager;                          # Entity Manager Class
use Next\DB\Entity\Repository;                       # Repository Class

/**
 * The SQLite Routes Generator Writer records all Routes Informations found in
 * an SQLite Database File
 *
 * @package    Next\HTPP
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Validation\Verifiable
 *             Next\Components\Object
 *             Next\DB\Driver\PDO\Adapter\SQLite as Adapter
 *             Next\DB\Entity\Manager
 *             Next\DB\Entity\Repository
 *             Next\HTTP\Router\Generators\Writer\Writer
 *             Next\HTTP\Router\Generators\Writer\SQLite\Entity
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
    protected function init() : void {

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
    public function save( array $data ) : int {

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
    public function reset() : void {
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
    public function verify() : void {

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