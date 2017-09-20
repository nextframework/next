<?php

/**
 * View Engine Helpers: Route | View\Helper\Route.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\View\Helper;

/**
 * Defines the Route View Helper that allows the creation of a full URL,
 * optionally wrapping it the the anchor tags automatically
 *
 * @package    Next\View\Helpers
 */
class Route implements Helper {

    /**
     * Builds a Route and optionally wraps it in a HTML anchor tag
     *
     * @param string $route
     *  The immutable portion of route
     *
     * @param array|optional $params
     *  An optional associative array if the variable key/value pairs
     *
     * @param string|optional $text
     *  An optional text. If defined indicates that an HTML anchor tag should
     *  be rendered
     *
     * @return string
     *  If <strong>$text</strong> is provided a full formed HTML anchor tag
     *  with built Route will be returned. Otherwise only the Route itself will.
     */
    public function __invoke( $route, array $params = [], $text = NULL ) {

        array_walk( $params, function( &$v, $k ) {
            $v = sprintf( '%s/%s', trim( $k ), trim( $v ) );
        });

        $route = sprintf( '%s/%s', trim( $route ), implode( $params, '/' ) );

        return ( is_null( $text ) ? $route : sprintf( '<a href="%s">%s</a>', $route, trim( $text ) ) );
    }

    // Helper Interface Method Implementation

    /**
     * Get the Helper name to be registered by View Engine
     *
     * @return string
     */
    public function getHelperName() {
        return 'route';
    }

    // OverLoading

    /**
     * Get the Helper name to be registered by View Engine
     *
     * @return string
     */
    public function __toString() {
        return $this -> getHelperName();
    }
}