<?php

namespace Next\View\Helper;

class Route implements Helper {

    /**
     * Builds a Route and optionally wraps it in a HTML anchor tag
     *
     * @param  string $route
     *  The immutable portion of route
     *
     * @param  array|optional $params
     *  An optional associative array if the variable key/value pairs
     *
     * @param  string|optional $text
     *  An optional text. If defined indicates that an HTML anchor tag should
     *  be rendered
     *
     * @return string
     *  If <strong>$text</strong> is provided a full formed HTML anchor tag
     *  with built Route will be returned. Otherwise only the Route itself will.
     */
    public function __invoke( $route, array $params = array(), $text = NULL ) {

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
}