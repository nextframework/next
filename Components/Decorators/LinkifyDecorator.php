<?php

/**
 * Decorators Component: Linkify | Components\Decorators\LinkifyDecorator.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Decorators;

/**
 * A linkify Decorator for rule based namespaced classes
 * and PHP Manual functions
 *
 * @package    Next\Components\Decorators
 */
class LinkifyDecorator extends AbstractDecorator {

    /**
     *  PHP Manual URL
     *
     *  @var string
     */
    const PHP_MANUAL_URL = 'http://www.php.net';

    /**
     * Namespaced Class/Function RGEXP
     *
     * @var string
     */
    const REGEXP = '(?:
                        \\\\?(?<namespace>(\\\\?\w+\\\\)+
                        \w+)
                        (?:(::)?)
                        (?:(?<method>\w+)\(\))?
                    |
                        (?<function>\w+)\(\)?
                    )';

    /**
     * Linkify Ruleset
     *
     * Every Class Linkify must have rules in both indexes:
     *
     * - 'namespace', for fully qualified namespaces WITHOUT a
     *   specific method (i.e. Next\Application\Application)
     * - 'full', for fully qualified namespaces PLUS a specific method
     *   AFTER '::' (i.e. Next\Application\Application::getRouter())
     *
     * @var array $rules
     */
    private $rules = [

        'namespace' => [

            /**
             * @internal
             *
             * Next Framework API Documentation with phpDocumentor 2. E.g:
             *
             * Next\Application\Application translates to
             *     next.application.application.html
             */
            'Next\\\\' => [

                'url'      => 'http://nextframework.github.io/api',
                'format'   => '<a href="%1$s/%4$s.html">%2$s</a>'
            ],
        ],

        'full' => [

            /**
             * @internal
             *
             * Next Framework API Documentation with phpDocumentor 2. E.g:
             *
             * Next\Application\Application::getRouter() translates to
             *     next.application.application.html#method_getRouter
             */
            'Next\\\\' => [

                'url'      => 'http://nextframework.github.io/api',
                'format'   => '<a href="%1$s/%4$s.html#method_%3$s">%2$s::%3$s()</a>'
            ],
        ],
    ];

    /**
     * List of PHP Function and/or resources with hotlinks
     * on PHP Manual (i.e. http://www.php.net/{function})
     *
     * @var array $functions
     */
    private $functions = [];

    /**
     * Additional Initialization.
     *
     * Lists all PHP Functions and/or resources with hotlinks on PHP Manual
     */
    public function init() {

        $functions = get_defined_functions();
        $functions = $functions['internal'];

        $this -> functions = array_merge( $functions,

            [
                '__halt_compiler()', 'array', 'die', 'echo', 'empty', 'eval',
                'exit', 'include', 'include_once', 'isset', 'list', 'print',
                'require', 'require_once', 'return', 'unset'
            ]
        );
    }

    // Decorator Interface Method Implementation

    /**
     *  Decorate Resource
     *
     *  Replaces all internal functions and class' methods with links pointing to PHP Manual
     *
     *  @return \Next\Components\Exception\ErrorExceptionDecorator
     *    LinkifyDecorator Instance (Fluent Interface)
     */
    public function decorate() {

        $this -> resource = preg_replace_callback(

            sprintf( '#%s#x', self::REGEXP ),

            function( $matches ) {

                if( array_key_exists( 'function', $matches ) ) {

                    return $this -> linkifyFunctions( $matches['function'] );

                } else {

                    return $this -> linkifyClasses(

                        $matches['namespace'],

                        ( array_key_exists( 'method', $matches ) ? $matches['method'] : NULL )
                    );
                }
            },

            $this -> resource
        );

        return $this;
    }

    // Auxiliary Methods

    /**
     * Linkify Classes and, optionally, methods
     *
     * @param  string $namespace
     *  Class namespace
     *
     * @param  string|optional $method
     *  Optional Class' method
     *
     * @return string
     *  Decoratable resource with found classes/methods linkified
     */
    private function linkifyClasses( $namespace, $method = NULL ) {

        $ruleset = ( empty( $method ) ? $this -> rules['namespace'] : $this -> rules['full'] );

        foreach( $ruleset as $rule => $data ) {

            if( preg_match( sprintf( '/%s/', $rule ), $namespace ) != 0 ) {

                /**
                 *  @internal
                 *
                 *  In order to use them the rule defined in 'format' index
                 *  should use argument swapping, as described in sprintf() docs
                 *
                 * The following arguments will be available, in the following order:
                 *
                 *  - Match URL
                 *  - Fully Qualified Namespace (backlashes preserved)
                 *  - Method name
                 *  - Fully Qualified Namespace (backslashes changed to dots)
                 */
                return sprintf(

                    $data['format'],

                    $data['url'], $namespace, $method,

                    str_replace( '\\', '.', $namespace ), $method
                );
            }
        }

        return ( empty( $method ) ? $namespace : sprintf( '%s\%s::%s()', $namespace, $method ) );
    }

    /**
     * Linkify (PHP) Functions
     *
     * @param  string $expression
     *  Expression where to look for (PHP) Functions to linkify
     *
     * @return string
     *  Decoratable resource with (PHP) Functions linkified
     */
    private function linkifyFunctions( $expression ) {

        // Linking standalone functions/resources

        $exp = trim( $expression, '()' );

        if( in_array( $exp, $this -> functions ) ) {

            return sprintf(

                '<a href="%s/%s">%s()</a>',

                self::PHP_MANUAL_URL, $exp, $exp
            );

        }

        // Not a recognized PHP function/resource

        return sprintf( '%s()', $expression );
    }
}