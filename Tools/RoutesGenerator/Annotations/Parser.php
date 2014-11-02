<?php

namespace Next\Tools\RoutesGenerator\Annotations;

use Next\Tools\RoutesGenerator\RoutesGeneratorException;    # Router Generator Exception Class
use Next\Components\Utils\ArrayUtils;                       # Array Utils Class

/**
 * Annotation Routes Generator: Annotations Parser
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Parser {

    /**
     * Locale Parameter Data
     *
     * @var string
     */
    const LOCALE_PARAM           = 'lang';

    /**
     * Delimiter Capture
     *
     * @var string
     */
    const DELIM_CAPTURE_TOKEN    = '(.*?)';

    /**
     * Parsed Data
     *
     * @staticvar array $results
     */
    private $results;

    /**
     * Annotations Parser Constructor
     *
     * Parses found Routes
     *
     * @param string|array $routes
     *  Route(s) to be parsed
     *
     * @param array $args
     *  Routes Argument(s)
     *
     * @param string $controller
     *  Controller to whom belongs the Route(s)
     *
     * @param string $method
     *  Method to whom belongs the Route(s)
     *
     * @param string $basepath
     *  Routes Basepath, prepended to every route
     */
    public function __construct( array $routes, array $args, $controller, $method, $basepath = '' ) {

        $this -> parseRoutes( $routes, $args, $controller, $method, $basepath );
    }

    // Accessors

    /**
     * Get parsed results
     *
     * @return array Routes Data
     */
    public function getResults() {
        return $this -> results;
    }

    // Auxiliary Methods

    /**
     * Parse one or more Routes, recursively
     *
     * @param string|array $routes
     *  Route(s) to be parsed
     *
     * @param array $args
     *  Routes Argument(s)
     *
     * @param string $controller
     *  Controller to whom belongs the Route(s)
     *
     * @param string $method
     *  Method to whom belongs the Route(s)
     *
     * @throws Next\Tools\RoutesGenerator\RoutesGeneratorException
     *  Route has less than 2 Components (a Request Method and a Route)
     *
     * @throws Next\Tools\RoutesGenerator\RoutesGeneratorException
     *  Routes defined as single slash (usually for homepages) DO have
     *  arguments (hierarchy concept)
     *
     * @throws Next\Tools\RoutesGenerator\RoutesGeneratorException
     *  There is another Route with exactly the same definition, including
     *  the Request Method
     */
    private function parseRoutes( $routes, array $args, $controller, $method, $basepath ) {

        foreach( $routes as $route ) {

            // Listing Route Components

            $components = explode( ',', $route );

            if( count( $components ) < 2 ) {

                throw RoutesGeneratorException::invalidRouteStructure(

                    array( $route, basename( $controller ), $method )
                );
            }

            // Shifting Request Method

            $requestMethod = trim( array_shift( $components ) );

            // ... and URI Route

            $URI = trim( array_shift( $components ) );

            // Parsing, fixing and complementing them

                /**
                 * @internal
                 * If defined URI is NOT a single slash, no trailing slash for it
                 * But add a RegExp Border instead
                 */

            if( $URI != '/' ) {

                // Prepending Routes Basepath if present

                if( empty( $basepath ) ) {

                    $URI = sprintf( '%s\b', trim( $URI, '/' ) );

                } else {

                    $URI = sprintf( '%s/%s\b', trim( $basepath, '/' ), trim( $URI, '/' ) );
                }

                /**
                 * @internal
                 * If we have a well designed structure, let's add RegExp Delim Captures Token too
                 *
                 * Routes pointing to a single slash do not have this token due hierarchical logic
                 * These kind of Routes cannot even have any params, except the one reserved for Localization
                 */
                $URI .= self::DELIM_CAPTURE_TOKEN;

            } else {

                // Let's ensure single slash Routes have no params

                if( count( $args ) != 0 ) {

                    throw RoutesGeneratorException::malformedRoute(

                        array( $URI, basename( $controller ), $method )
                    );
                }
            }

            // Let's parse Required and Optional Params

            $required = $this -> parseParams( $args, 'required' );
            $optional = $this -> parseParams( $args, 'optional' );

            // Adding an always optional parameter reserved for Localization

            $optional[] = array(
                    'name'      => self::LOCALE_PARAM,
                    'type'      => 'optional',
                    'default'   => 'en'
            );

            // Searching for Duplicates

            $offset = ArrayUtils::search( $this -> results, $URI, 'route' );

            // We found one...

            if( $offset != -1 ) {

                // ... let's compare with the Request Method

                if( $this -> results[ $offset ]['requestMethod'] == $requestMethod ) {

                    // Yeah! We have a Duplicate

                    throw RoutesGeneratorException::duplicatedRoute(

                        array( $requestMethod, $URI, basename( $controller ), $method )
                    );
                }
            }

            // Preparing Parsed Route to be recorded

            $this -> results[] = array(

                'requestMethod'    => $requestMethod,
                'route'            => $URI,

                'params'           => array(

                                          'required' => $required,
                                          'optional' => $optional
                                      ),
            );
        }
    }

    /**
     * Parse Route Parameters
     *
     * @param array $params
     *  Route's Params to parse/filter
     *
     * @param string $token
     *  Token to be used as Parsing Anchor
     *
     * @return array
     *  Filtered list in according to given Token
     */
    private function parseParams( $params, $token ) {

        return array_filter(

            $params,

            function( $param ) use( $token ) {
                return ( $param['type'] == $token );
            }
        );
    }
}