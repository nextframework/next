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
    public function __construct( Applications $applications, $dbPath/*, Driver $driver*/ ) {

        parent::__construct( $applications );

        // Routes Database Table Mapper

        //$this -> manager = new Manager( $driver, new Annotations\Table );
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

        foreach( $this -> applications as $applications ) {

            $annotations = new Annotations\Applications( $applications );

            // Parse Routes

            $annotations = $annotations -> getAnnotations();

            if( $annotations -> offsetExists( 'Routes' ) ) {

                // Listing routes found...

                $results = new Annotations\Parser(

                    $annotations -> offsetGet( 'Routes' ),

                    $annotations -> offsetGet( 'Path' )
                );

                // ... and building the final structure

                $this -> routes[ get_class( $applications ) ] = $results -> getResults();
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

        // Sorting Routes by Length...

        $this -> routes = array_map( array( $this, 'sort' ), $this -> routes );

        $records = 0;

        foreach( $this -> routes as $application => $routes ) {

            foreach( $routes as $route ) {

                // Setting Model Values

                $this -> manager -> setSource(

                    array(

                        'requestMethod'    => $route['requestMethod'],
                        'application'      => $application,
                        'class'            => $route['class'],
                        'method'           => $route['method'],
                        'URI'              => $route['route'],
                        'requiredParams'   => serialize( $route['params']['required'] ),
                        'optionalParams'   => serialize( $route['params']['optional'] )
                    )
                );

                // Cleaning Information of Previous Iteration

                $this -> manager -> reset();

                // Trying to insert

                try {

                    $this -> manager -> insert();

                    // Increment Counter

                    $records += 1;

                } catch( StatementException $e ) {

                    // Re-throw as RoutesDatabaseException

                    throw RoutesGeneratorException::recordingFailure(

                        array( $route['route'], $route['class'], $route['method'], $e -> getMessage() )
                    );
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

        usort(

            $routes,

            function( $a, $b ) {
                return ( strlen( $a['route'] ) > strlen( $b['route'] ) ? -1 : 1 );
            }
        );

        return $routes;
    }
}