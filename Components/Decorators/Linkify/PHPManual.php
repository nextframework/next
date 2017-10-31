<?php

/**
 * PHP Manual Linkify Decorator | Components\Decorators\LinkifyNextAPI.php
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
 * A Linkify Decorator for PHP Manual classes/functions
 *
 * @package    Next\Components\Decorators
 *
 * @uses       Next\Components\Decorators\Decorator
 *             Next\Components\Object
 *             Next\Components\Decorators\Linkify\Linkify
 */
class PHPManual extends Object implements Decorator, Linkify {

    /**
     * PHP Manual URL
     *
     *  @var string
     */
    const PHP_MANUAL_URL = 'http://www.php.net';

    /**
     * Linkify Rule.
     * For most of functions PHP Manual has a pretty straightforward URL:
     * `http://www.php.net/<function_name>`
     *
     * @var string
     */
    const PHP_MANUAL_LINK_FORMAT = '<a href="%1$s/%2$s">%2$s()</a>';

    /**
     * List of PHP Keywords, not necessarily functions, that also have hotlinks
     * on PHP Manual (i.e. http://www.php.net/{keyword})
     *
     * @var array
     */
    const PHP_KEYWORDS = [
        '__halt_compiler()', 'array', 'die', 'echo', 'empty', 'eval',
        'exit', 'include', 'include_once', 'isset', 'list', 'print',
        'require', 'require_once', 'return', 'unset'
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

                // No matches, return "as is"

                if( count( $matches ) == 0 ) return $matches[ 0 ];

                /**
                 * @internal
                 *
                 * Here we check if we DON'T HAVE a matching 'sro',
                 * the Scope Resolution Operator (::)
                 */
                if( empty( $matches['sro'] ) && ! empty( $matches['function'] ) ) {

                    $functions = array_merge(
                        get_defined_functions()['internal'], self::PHP_KEYWORDS
                    );

                    // Linking standalone functions/resources

                    $expression = trim( $matches['function'], '()' );

                    if( in_array( $expression, $functions ) ) {

                        /**
                         * @internal
                         *
                         * In order to use them the rule defined in the
                         * PHP_MANUAL_LINK_FORMAT constant *should* use argument
                         * swapping, as described in sprintf() docs
                         *
                         * The following arguments will be available, in the
                         * following order:
                         *
                         *  - Base URL
                         *  - Matching Expression
                         */
                        return sprintf(

                            self::PHP_MANUAL_LINK_FORMAT,

                            self::PHP_MANUAL_URL, $expression
                        );
                    }

                }

                // Not a recognized PHP function/resource, return "as is"

                return $matches[ 0 ];
            },

            $this -> options -> resource
        );
    }
}