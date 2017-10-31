<?php

/**
 * View Engine Helpers: Route | View\Helper\Route.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\View\Helpers;

use Next\Components\Object;

/**
 * The Route View Helper allows the creation of a full URL for a given URI,
 * optionally wrapping it in (X)HTML anchor tags automatically
 *
 * @package    Next\View
 *
 * @uses       Next\Components\Object
 *             Next\View\Helpers\Helper
 */
class Route extends Object Implements Helper {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'request' => [ 'type' => 'Next\HTTP\Request', 'required' => TRUE ]
    ];

    // Helper Interface Method Implementation

    /**
     * Builds a Route and optionally wraps it in a HTML anchor tag
     *
     * @param array $args
     *  Arguments passed from Template Views to the implementation that leads
     *  the Request Flow here (i.e `Next\View\Standard::call()`)
     *
     *  The first index is the route that'll have the BaseURL from provided
     *  Request Object prepended.
     *  Defaults to a single slash which *should* lead to the homepage, after
     *  all the developer have to create a Route for that
     *
     *  The second index is an associative array with a list of URL Parameters
     *  that'll be appended to the Route.
     *  Defaults to an empty list
     *
     *  The third index is a text to be displayed and when set (i.e not NULL)
     *  changes the return type from a single string to a full-formed HTML
     *  anchor tag
     *
     * @return string
     *  If there's a third argument defined and not NULL, a string with a
     *  full-formed HTML anchor tag will be returned.
     *  Otherwise only the assembled Route will
     */
    public function __invoke( array $args ) : string {

        list( $URI, $params, $text ) = $args + [ '/', [], NULL ];

        array_walk( $params, function( &$v, $k ) : void {
            $v = sprintf( '%s/%s', trim( $k ), trim( $v ) );
        });

        $route = implode( '/', array_filter(
            [ $this -> options -> request -> getBaseURL(), trim( $URI, '/' ), implode( '/', $params ) ]
        ));

        if( $text === NULL ) return $route;

        return sprintf( '<a href="%s">%s</a>', $route, trim( $text ) );
    }
}