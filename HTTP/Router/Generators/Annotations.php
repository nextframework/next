<?php

/**
 * Routes Generators Classes' Annotations Class | HTTP\Router\Generators\Annotations.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Router\Generators;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\LengthException;

use Next\Components\Object;                    # Object Class
use Next\Application\Chain as Applications;    # Application Chain Class

/**
 * The Annotations Generator puts together all Route informations found in
 * Page Controllers' doc-blocks and offers, additionally, the time taken to
 * parse all of them
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Exception\Exceptions\LengthException
 *             Next\Components\Object
 *             Next\Application\Chain
 *             Next\HTTP\Router\Generators\Generator
 */
class Annotations extends Object implements Generator {

    /**
     * Applications Chain
     *
     * @var \Next\Application\Chain $applications
     */
    protected $applications;

    /**
     * Routes Results
     *
     * @var array $results
     */
    protected $results = [];

    /**
     * Time Elapsed
     *
     * @var float $startTime
     */
    protected $startTime;

    /**
     * Routes Generator Constructor
     *
     * @param \Next\Application\Chain $applications
     *  Applications Chain to iterate through
     *
     * @see \Next\Components\Parameter
     */
    public function __construct( Applications $applications ) {

        // Start Time (for final message)

        $this -> startTime = microtime( TRUE );

        // Applications' Chain

        $this -> applications = $applications;

        parent::__construct();
    }

    // Generator Interface Methods Implementation

    /**
     * Find Routes from Controllers Methods DocBlocks
     *
     * To be included in analysis, methods must be PUBLIC and FINAL.
     *
     * The constructor is automatically ignored
     *
     * @return array
     *  Parsed data ready to be written
     *
     * @throws \Next\Exception\Exceptions\LengthException
     *  Thrown if no Routing Data has been found in one of the
     *  Action Methods
     */
    public function find() : array {

        $results = [];

        foreach( $this -> applications as $applications ) {

            $annotations = new Annotations\Applications( $applications );
            $annotations = $annotations -> getAnnotations();

            $domain = $annotations -> offsetGet( 'domain' );
            $annotations -> offsetUnset( 'domain' ); // Not elegant, but...

            $path = $annotations -> offsetGet( 'path');
            $annotations -> offsetUnset( 'path');   // Not elegant, but ...

            $foundAnnotations = [];

            foreach( $annotations as $classname => $annotations ) {

                foreach( $annotations as $method => $data ) {

                    if( count( $data['routes'] ) == 0 ) {

                        throw new LengthException(

                            sprintf(

                                'No Routes found for Action Method <em>%s</em> of Controller class <em>%s</em>',

                                $method, $classname
                            )
                        );
                    }

                    // Parsing Routes...

                    $foundAnnotations = new Annotations\Parser(

                        $data['routes'], $data['args'], $classname, $method, $domain, $path
                    );

                    // ... and building final structure

                    $results[ $applications -> getClass() -> getName() ][ $classname ][ $method ] = $this -> sort( $foundAnnotations -> getResults() );
                }
            }
        }

        return $results;
    }

    /**
     * Get elapsed time
     *
     * @return float
     *  Elapsed time since the object of the chosen Generator was created
     */
    public function getElapsedTime() : float {
        return ( microtime( TRUE ) - $this -> startTime );
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
     *  Found Routes
     *
     * @return array
     *  Routes Data-source now sorted, from the longest to the shortest
     */
    private function sort( array $routes ) : array {

        uasort(

            $routes,

            function( $a, $b ) : int {
                return ( mb_strlen( $a['route'] ) <=> mb_strlen( $b['route'] ) );
            }
        );

        return $routes;
    }
}