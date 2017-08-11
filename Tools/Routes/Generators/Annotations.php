<?php

/**
 * Routes Generators Classes' Annotations Class | Tools\Generators\Annotations.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Tools\Routes\Generators;

use Next\Tools\Routes\Generators\GeneratorsException;    # Routes Generator Exception Class

/**
 * Routes Generator Tool: Annotations Generator
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Annotations extends AbstractGenerator {

    /**
     * Find Routes from Controllers Methods DocBlocks
     *
     * To be included in analysis, methods must be PUBLIC and FINAL.
     *
     * The constructor is automatically ignored
     *
     * @return array
     *  Parsed data ready to be written
     */
    public function find() {

        $results = array();

        foreach( $this -> applications as $applications ) {

            $annotations = new Annotations\Applications( $applications );
            $annotations = $annotations -> getAnnotations();

            $domain = $annotations -> offsetGet( 'domain' );
            $annotations -> offsetUnset( 'domain' ); // Not elegant, but...

            $path = $annotations -> offsetGet( 'path');
            $annotations -> offsetUnset( 'path');   // Not elegant, but ...

            $foundAnnotations = array();

            foreach( $annotations as $classname => $annotations ) {

                foreach( $annotations as $method => $data ) {

                    if( count( $data['routes'] ) == 0 ) {
                        throw GeneratorsException::noRoutes( array( $classname, $method ) );
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
     *  Routes Data Source now sorted, from the longest to the shortest
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