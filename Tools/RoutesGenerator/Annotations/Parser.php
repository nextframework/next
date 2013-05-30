<?php

namespace Next\Tools\RoutesGenerator\Annotations;

use Next\Tools\RoutesGenerator\RoutesGeneratorException;          # Router Generator Exception Class
use Next\Components\Utils\ArrayUtils;                             # Array Utils Class

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
     * Locale Parameter
     *
     * @var string
     */
    const LOCALE_PARAM           = ':lang';

    /**
     * Delimiter Capture
     *
     * @var string
     */
    const DELIM_CAPTURE_TOKEN    = '(.*?)';

    /**
     * Optional Arguments
     *
     * @var string
     */
    const OPTIONAL_PARAMS_TOKEN  = ':';

    /**
     * Required Arguments
     *
     * @var string
     */
    const REQUIRED_PARAMS_TOKEN  = '$';

    /**
     * Default Values Separator
     *
     * @var string
     */
    const DEFAULT_VALUE_TOKEN    = '|';

    /**
     * Parsed Data
     *
     * @var array $results
     */
    private $results;

    /**
     * Annotations Parser Constructor
     *
     * Iterates through a list of Controllers to parse the Routes
     * found inside their Methods
     *
     * @param array $controllers
     *   Application Controlers
     *
     * @param string $basepath
     *   Routes Basepath, prepended to every route
     */
    public function __construct( array $controllers, $basepath = '' ) {

        foreach( $controllers as $controller ) {

            $c = key( $controller );

            foreach( $controller as $methods ) {

                foreach( $methods as $method => $routes ) {

                    $this -> parseRoutes( $routes, $c, $method, $basepath );
                }
            }
        }
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
     *   Route(s) to be parsed
     *
     * @param string $controller
     *   Controller to whom belongs the Route(s)
     *
     * @param string $method
     *   Method to whom belongs the Route(s)
     *
     * @throws Next\Tools\RoutesGenerator\RoutesGeneratorException
     *   Route has less than 2 Components (a Request Method and a Route)
     *
     * @throws Next\Tools\RoutesGenerator\RoutesGeneratorException
     *   Routes defined as single slash (usually for homepages) DO have
     *   arguments (hierarchy concept)
     *
     * @throws Next\Tools\RoutesGenerator\RoutesGeneratorException
     *   There is another Route with exactly the same definition, including
     *   the Request Method
     */
    private function parseRoutes( $routes, $controller, $method, $basepath ) {

        if( is_array( $routes ) ) {

            foreach( $routes as $route ) {

                $this -> parseRoutes( $route, $controller, $method, $basepath );
            }

        } else {

            // Listing Route Components

            $components = explode( ',', $routes );

            if( count( $components ) < 2 ) {

                throw RoutesGeneratorException::invalidRouteStructure(

                    array( $routes, basename( $controller ), $method )
                );
            }

            // Shifting Request Method

            $requestMethod = trim( array_shift( $components ) );

            // ... and URI Route

            $URI = trim( array_shift( $components ) );

            // If we still have some components, all them will be treated as Route Params

            $params = array();

            if( count( $components ) > 0 ) {

                $params = array_map( 'trim', $components );
            }

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

                if( ! empty( $params ) ) {

                    throw RoutesGeneratorException::malformedRoute(

                        array( $URI, basename( $controller ), $method )
                    );
                }
            }

            // Adding an Always Optional Parameter for Localization

            $params[] = self::LOCALE_PARAM;

            // Let's parse Required and Optional Params

            $required = $this -> parseParams( $params, self::REQUIRED_PARAMS_TOKEN );
            $optional = $this -> parseParams( $params, self::OPTIONAL_PARAMS_TOKEN );

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

                'class'            => $controller,
                'method'           => $method
            );
        }
    }

    /**
     * Parse Route Parameters
     *
     * @param array $params
     *   Route's Params to parse/filter
     *
     * @param string $token
     *   Token to be used as Parsing Anchor
     *
     * @return array
     *   Filtered list in according to given Token
     */
    private function parseParams( $params, $token ) {

        return array_filter(

            $params,

            function( $param ) use( $token ) {

                return substr( $param, 0, 1 ) == $token;
            }
        );
    }
}