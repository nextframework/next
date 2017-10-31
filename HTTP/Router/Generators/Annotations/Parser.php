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

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Object;              # Object Class
use Next\Components\Utils\ArrayUtils;    # Array Utils Class

/**
 * The Routes Generator Parser lists, checks and prepares the final
 * data-structure with all Application Controllers' and Page Controllers'
 * Action Methods Annotations for the Routes Generator to process
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Object
 *             Next\Components\Utils\ArrayUtils
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
     * @var array $results
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
     */
    public function __construct( array $routes, array $args, $controller, $method, $domain = '', $basepath = '' ) {

        parent::__construct();

        $this -> parseRoutes( $routes, $args, $controller, $method, $domain, $basepath );
    }

    // Accessors

    /**
     * Get parsed results
     *
     * @return array
     *  Routes Data, after parsing
     */
    public function getResults() :? array {
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
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     * Thrown if an Action Method has an malformed Route Definition,
     *  with has less than 2 Components — a Request Method and a URI
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if an Action Method with a Route defined as a single
     *  slash (i.e for a homepage) DO have Route Arguments which is
     *  forbidden as for a hierarchy concept
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if there's another Route with exactly the same
     *  definition, including the Request Method
     *
     *  Note: that this checking is Controller-aware, which means that
     *  Duplicated Routes across different Controller won't be warned.
     *  In these cases, both Routes will be recorded but only the one
     *  defined earlier will be used as it will be the first match
     *  when the chosen `Next\HTTP\Router\Router` is finding a
     *  matching Route
     */
    private function parseRoutes( $routes, array $args, $controller, $method, $domain, $basepath ) : void {

        foreach( $routes as $route ) {

            // Listing Route Components

            $components = explode( ',', $route );

            if( count( $components ) < 2 ) {

                throw new InvalidArgumentException(

                    sprintf(

                        'Route <em>%s</em> defined in Action Method
                        <em>%s</em> of Controller class <em>%s</em>
                        has a invalid Route Structure

                        All Routes\' Definitions must be composed of at
                        least two components: a <strong>Request Method</strong>
                        and a <strong>URI</strong>',

                        $route, $method, basename( $controller )
                    )
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

                throw new InvalidArgumentException(

                    sprintf(

                        'Action Method <em>%s</em> of Controller class
                        <em>%s</em> has been defined as a single slash
                        and, therefore, cannot have any Routing Arguments
                        in order to follow a hierarchy logic',

                        $method, basename( $controller )
                    )
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

                    throw new InvalidArgumentException(

                        sprintf(

                            'Route <em>%s</em> has already been defined
                            for Request Method <em>%s</em>',

                            $URI, $requestMethod
                        )
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
    private function parseParams( $params, $token ) : array {

        return array_filter(

            $params,

            function( $param ) use( $token ) : bool {
                return ( $param['type'] == $token );
            }
        );
    }
}