<?php

/**
 * Next Framework Linkify Decorator | Components\Decorators\Linkify\NextAPI.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Decorators\Linkify;

use Next\Components\Decorators\Decorator;    # Decorator Interface
use Next\Components\Object;                  # Object Class

/**
 * A Linkify Decorator for Next Framework API
 *
 * @package    Next\Components\Decorators
 *
 * @uses       Next\Components\Decorators\Decorator
 *             Next\Components\Object
 *             Next\Components\Decorators\Linkify\Linkify
 */
class NextAPI extends Object implements Decorator, Linkify {

    /**
     * Next Framework Top Level Namespace
     *
     * @var string
     */
    const NEXT_TOP_LEVEL_NAMESPACE = 'Next\\';

    /**
     * Next Framework API URL
     *
     *  @var string
     */
    const NEXT_API_URL = 'http://nextframework.github.io/api';

    /**
     * Linkify RuleSet
     * An associative array containing two entries:
     *
     * - **namespace**, for fully qualified namespaces WITHOUT a
     *   specific method (i.e. `Next\Application\APPLICATION`)
     *
     * - **full**, for fully qualified namespaces PLUS a specific method
     *   AFTER '::' (i.e. `Next\Application\Application::getRouter()`)
     *
     * @var array
     */
    const RULES = [

        /**
         * @internal
         *
         * Next Framework API Documentation generated with SAMI and translates
         * classnames like `Next\Application\Application` to `Next/Application/Application.html`
         */
        'namespace' => '<a href="%1$s/%3$s.html">%2$s</a>',

        /**
         * @internal
         *
         * Next Framework API Documentation generated with SAMI translates
         * classnames like `Next\Application\Application::getRouter()` to
         * `Next/Application/Application.html#method_getRouter`
         */
        'full' => '<a href="%1$s/%3$s.html#method_%5$s">%2$s::%5$s()</a>'
    ];

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        // Decoratable resource

        'resource' => [ 'required' => TRUE ]
    ];

    // Decorator Interface Method Implementation

    /**
     * Get decorated resource
     *
     * @return mixed
     *  Decorated Resource
     */
    public function getResource() : string {

        // Decorating...

        $this -> options -> resource = $this -> decorate();

        return $this -> options -> resource;
    }

    /**
     * Decorates Resource.
     * Replaces all internal functions and class' methods with links pointing to PHP Manual
     *
     * @return string
     *  Provided resource 'linkifyed'
     */
    public function decorate() : string {

        return preg_replace_callback(

            sprintf( '#%s#x', Linkify::REGEXP ),

            function( $matches ) : string {

                // No matches or missing namespace, return "as is"

                if( count( $matches ) == 0 || empty( $matches['namespace'] ) ) return $matches[ 0 ];

                if( ! empty( $matches['method'] ) ) {

                    $method = $matches['method'];
                    $format = self::RULES['full'];

                } else {

                    $method = NULL;
                    $format = self::RULES['namespace'];
                }

                if( strpos( $matches['namespace'], self::NEXT_TOP_LEVEL_NAMESPACE ) !== FALSE ) {

                    /**
                     * @internal
                     *
                     * In order to use them the rule defined in 'format' index
                     * should use argument swapping, as described in sprintf() docs
                     *
                     * The following arguments will be available, in the following order:
                     *
                     *  - Base URL
                     *  - Fully Qualified Namespace (backlashes preserved)
                     *  - Fully Qualified Namespace (backlashes changes to normal slashes)
                     *  - Fully Qualified Namespace (backslashes changed to dots)
                     *  - Method name
                     */
                    return sprintf(

                        $format,

                        self::NEXT_API_URL,

                        $matches['namespace'],

                        strtr( $matches['namespace'], [ '\\' => '/' ] ),

                        strtr( $matches['namespace'], [ '\\' => '.' ] ),

                        $method
                    );
                }

                // Not a class recognized by part of Next Framework, return "as is"

                return $matches[ 0 ];
            },

            $this -> options -> resource
        );
    }
}