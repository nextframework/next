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
 * A linkify Decorator for Next Framework API
 *
 * @package    Next\Components\Decorators
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
     * An associative containing two entries:
     *
     * - 'namespace', for fully qualified namespaces WITHOUT a
     *   specific method (i.e. Next\Application\Application)
     *
     * - 'full', for fully qualified namespaces PLUS a specific method
     *   AFTER '::' (i.e. Next\Application\Application::getRouter())
     *
     * @var array
     */
    const RULES = [

        /**
         * @internal
         *
         * Next Framework API Documentation generated with
         * phpDocumentor 2 for Classname translates
         * `Next\Application\Application` into `next.application.application.html`
         */
        'namespace' => '<a href="%1$s/%4$s.html">%2$s</a>',

        /**
         * @internal
         *
         * Next Framework API Documentation generated with
         * phpDocumentor 2 for Classname translates
         * `Next\Application\Application::getRouter()` into
         * `next.application.application.html`#method_getRouter
         */
        'full' => '<a href="%1$s/%4$s.html#method_%3$s">%2$s::%3$s()</a>'
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
    public function getResource() {

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
    public function decorate() {

        return preg_replace_callback(

            sprintf( '#%s#x', Linkify::REGEXP ),

            function( $matches ) {

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
                     *  - Match URL
                     *  - Fully Qualified Namespace (backlashes preserved)
                     *  - Method name
                     *  - Fully Qualified Namespace (backslashes changed to dots)
                     */
                    return sprintf(

                        $format,

                        self::NEXT_API_URL, $matches['namespace'], $method,

                        str_replace( '\\', '.', $matches['namespace'] ), $method
                    );
                }

                // No a class recognized by part of Next Framework, return "as is"

                return $matches[ 0 ];
            },

            $this -> options -> resource
        );
    }
}