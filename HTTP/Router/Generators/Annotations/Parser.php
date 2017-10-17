<?php

/**
 * Routes Generator Annotations Parser Class | HTTP\Router\Generators\Annotation\Parser.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Router\Generators\Annotations;

use Next\Tools\Routes\Generators\GeneratorsException;    # Routes Generators Exception Class

use Next\Components\Object;                              # Object Class
use Next\Components\Utils\ArrayUtils;                    # Array Utils Class

/**
 * Defines the Routes Parser, listing, checking and preparing
 * structure for the Routes Generator process
 *
 * @package    Next\HTTP
 */
class Parser extends Object {

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
     *  Controller's classname where the Route has been found
     *
     * @param string $method
     *  Method's name where the Route has been found
     *
     * @param string|optional
     * Routes' Domain, prepended to every route
     *
     * @param string|optional $basepath
     *  Routes' Basepath, appended to every route
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for Annotations Parser
     */
    public function __construct( array $routes, array $args, $controller, $method, $domain = '', $basepath = '', $options = NULL ) {

        parent::__construct( $options );

        $this -> parseRoutes( $routes, $args, $controller, $method, $domain, $basepath );
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
     *  Controller's classname where the Route has been found
     *
     * @param string $method
     *  Method's name where the Route has been found
     *
     * @param string
     * Routes' Domain, prepended to every route
     *
     * @param string $basepath
     *  Routes' Basepath, appended to every route
     *
     * @throws \Next\HTTP\Router\Generators\GeneratorsException
     *  Route has less than 2 Components (a Request Method and a Route)
     *
     * @throws \Next\HTTP\Router\Generators\GeneratorsException
     *  Routes defined as single slash (usually for homepages) DO have
     *  arguments (hierarchy concept)
     *
     * @throws \Next\HTTP\Router\Generators\GeneratorsException
     *  There is another Route with exactly the same definition, including
     *  the Request Method
     */
    private function parseRoutes( $routes, array $args, $controller, $method, $domain, $basepath ) {

        foreach( $routes as $route ) {

            // Listing Route Components

            $components = explode( ',', $route );

            if( count( $components ) < 2 ) {

                throw GeneratorsException::invalidRouteStructure(
                    [ $route, basename( $controller ), $method ]
                );
            }

            // Shifting Request Method

            $requestMethod = trim( array_shift( $components ) );

            // ... and URI Route

            $URI = trim( array_shift( $components ) );

            /**
             * @internal
             *
             * Routes pointing to a single slash can't have any params other
             * than the reserved for Localization token due hierarchical logic
             */
            if( $URI =='/' && count( $args ) > 0 ) {

                // Let's ensure single slash Routes have no params

                throw GeneratorsException::malformedRoute(

                    [ $URI, basename( $controller ), $method ]
                );
            }

            /**
             * @internal
             * Now that we have well designed structure, let's add
             * RegExp Delim Capture Token too
             */
            if( $URI != '/' ) {
                $URI .= self::DELIM_CAPTURE_TOKEN;
            }

            /**
             * @internal
             * If defined URI is NOT a single slash, let's add a RegEXp border to it
             */
            if( $URI != '/' ) $URI .= '\b';

            // Parsing, fixing and complementing them

                // Appending Routes Basepath if present

            if( empty( $basepath ) ) {

                $URI = sprintf( '%s', ( $URI != '/' ? trim( $URI, '/' ) : $URI ) );

            } else {

                $URI = sprintf( '%s/%s', trim( $basepath, '/' ), trim( $URI, '/' ) );
            }

                // Adding Domain

            if( ! empty( $domain ) ) {
                $URI = trim( sprintf( '%s/%s', $domain, $URI ), '/' );
            }

            // Let's parse Required and Optional Params

            $required = $this -> parseParams( $args, 'required' );
            $optional = $this -> parseParams( $args, 'optional' );

            // Adding an always optional parameter reserved for Localization

            $optional[] = [
                'name'      => self::LOCALE_PARAM,
                'type'      => 'optional',
                'default'   => 'en'
            ];

            // Searching for Duplicates

            $offset = ArrayUtils::search( $this -> results, $URI, 'route' );

            // We found one...

            if( $offset != -1 ) {

                // ... let's compare with the Request Method

                if( $this -> results[ $offset ]['requestMethod'] == $requestMethod ) {

                    // Yeah! We have a Duplicate

                    throw GeneratorsException::duplicatedRoute(

                        [ $requestMethod, $URI, basename( $controller ), $method ]
                    );
                }
            }

            // Preparing Parsed Route to be recorded

            $this -> results[] = [

                'requestMethod'    => $requestMethod,
                'route'            => $URI,

                'params'           => array(
                    'required' => $required,
                    'optional' => $optional
                ),
            ];
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