<?php

namespace Next\Tools\RoutesGenerator;

use Next\DB\Statement\StatementException;                   # SQL Statement Exception Class
use Next\Tools\RoutesGenerator\RoutesGeneratorException;    # Routes Generator Exception Class
use Next\Application\Chain as Applications;                 # Application Chain Class
use Next\DB\Table\Manager;                                  # Table Manager
use Next\DB\Driver\Driver;                                  # Connection Driver Interface

/**
 * Routes Generator Tool: Annotations Generator
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Annotations extends AbstractRoutesGenerator {

    /**
     * Table Manager Object
     *
     * @var Next\DB\Table\Manager $manager
     */
    private $manager;

    /**
     * Routes Generator from Annotations Constructor.
     *
     * Adapts parents constructor to require a Database Connection Driver
     * to be used in Table Manager
     *
     * @param Next\Application\Chain $applications
     *   Applications Chain to iterate through
     *
     * @param string $dbPath
     *   SQLite Database Filepath
     */
    public function __construct( Applications $applications, $dbPath ) {

        parent::__construct( $applications );

        // Routes Database Table Mapper

        $this -> manager = new Manager(

            new \Next\DB\Driver\PDO\Adapter\SQLite(

                array( 'dbPath' => $dbPath )
            ),

            new Annotations\Table
        );
    }

    /**
     * Find Routes from Controllers Methods DocBlocks
     *
     * To be included in analysis, methods must be PUBLIC and FINAL.
     *
     * The constructor is automatically ignored
     *
     * @return Next\Tools\RoutesGenerator\Annotations
     *   Routes Generator Annotations Object (Fluent Interface)
     */
    public function find() {

        $results = array();

        foreach( $this -> applications as $applications ) {

            $annotations = new Annotations\Applications( $applications );
            $annotations = $annotations -> getAnnotations();

            $path = $annotations -> offsetGet( 'path');
            $annotations -> offsetUnset( 'path');   // Not elegant, but ...

            $foundAnnotations = array();

            foreach( $annotations as $classname => $annotations ) {

                foreach( $annotations as $method => $data ) {

                    if( count( $data ['routes'] ) == 0 ) {
                        throw RoutesGeneratorException::noRoutes( array( $classname, $method ) );
                    }

                    // Parsing Routes...

                    $foundAnnotations = new Annotations\Parser(

                        $data['routes'], $data['args'], $classname, $method, $path
                    );

                    // ... and building final structure

                    $this -> results[ $applications -> getClass() -> getName() ][ $classname ][ $method ] = $this -> sort( $foundAnnotations -> getResults() );
                }
            }
        }

        return $this;
    }

    /**
     * Save them, to be used by Router Classes
     *
     * @throws Next\Tools\RoutesGerenerator\RoutesGeneratorException
     *   Unable to record route
     */
    public function save() {

        set_time_limit( 0 );

        $records = 0;

        foreach( $this -> results as $application => $controllersData ) {

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

                            throw RoutesGeneratorException::recordingFailure(

                                array( $d['route'], $controller, $action, $e -> getMessage() )
                            );
                        }
                    }
                }
            }
        }

        // Final Message

        printf(

            '%s Routes analyzed, parsed and recorded in %f seconds',

            $records, ( microtime( TRUE ) - $this -> startTime )
        );
    }

    /**
     * Reset the Routes Storage, by formatting or cleaning it
     *
     * @return integer
     */
    public function reset() {
        return $this -> manager -> delete() -> rowCount();
    }

    // Auxiliary methods

    /**
     * Sort Routes
     *
     * This is needed because, at least in Standard's Router, we use preg_match()
     * as function implementation to SQLITE's REGEXP operator
     *
     * But, unlike deprecated POSIX functions (like ereg), preg_match
     * stops in the first positive match.
     *
     * But here, if the match stops in the first positive match, long routes
     * will never be reached, because can potentially be matched as a shorter
     * route.
     *
     * E.g:
     *
     * Considering these two routes, from a hypothetical movies database system:
     *
     * <em>/manager/actors and /manager/actors/add</em>
     *
     * First route refers to a small dashboard, for all actions related to
     * Actor/Actress Management
     *
     * The second route refers to the form page to add a new actor/actress
     *
     * Without this sorting below, by accessing the second route we would wrongly
     * be redirected to the URL defined in the first one, because both of them
     * have <strong>/manager/actors</strong>.
     *
     * The sorting below, is to send the longest routes (more specific) to the top,
     * and shortest routes (more generic) to the bottom, minimizing the conflicts.
     *
     * @param array $routes
     *   Found Routes
     *
     * @return array
     *   Routes Data Source now sorted, from the longest to the shortest
     */
    private function sort( array $routes ) {

        uasort(

            $routes,

            function( $a, $b ) {
                return ( strlen( $a['route'] ) > strlen( $b['route'] ) ? -1 : 1 );
            }
        );

        return $routes;
    }
}