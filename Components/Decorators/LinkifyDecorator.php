<?php

namespace Next\Components\Decorators;

class LinkifyDecorator extends AbstractDecorator {

    /**
     *  PHP Manual URL
     *
     *  @var string
     */
    const PHP_MANUAL_URL = 'http://www.php.net';

    private $rules = array(

        'Next\\.*' => array(

            'url'      => 'http://www.notready.com',
            'format'   => '<a href="%1$s/%4$s.%5$s">%2$s::%3$s()</a>'
        )
    );

    // Decorator Interface Method Implementation

    /**
     *  Decorate Resource
     *
     *  Replaces all internal functions and class' methods with links pointing to PHP Manual
     *
     *  @return Next\Components\Exception\ErrorExceptionDecorator
     *    LinkifyDecorator Instance (Fluent Interface)
     */
    public function decorate() {

        $that =& $this;

        $this -> resource = preg_replace_callback(

            '/((?<object>(\w+\\\\?)+)::)?(?<function>\w+)\(\)/', #'/\s?(.*?)\(\)/',

            function( $matches ) use( $that ) {

                if( array_key_exists( 'function', $matches ) && ! empty( $matches['function'] ) ) {

                    // Linkifying Classes

                    if( array_key_exists( 'object', $matches ) && ! empty( $matches['object'] ) ) {

                        return $this -> linkifyClasses(

                            $matches['object'], $matches['function']
                        );
                    }

                    // Linkyfying Functions

                    return $this -> linkifyFunctions( $matches['function'] );
                }
            },

            $this -> resource
        );

        return $this;
    }

    // Auxiliary Methods

    private function linkifyClasses( $class, $method ) {

        foreach( $this -> rules as $rule => $data ) {

            if( preg_match( sprintf( '/%s/', $rule ), $class ) != 0 ) {

                /**
                 *  Available Arguments
                 *
                 *  In order to use them the rule defined in 'format' index
                 *  should use argument swapping, as described in sprintf() docs
                 *
                 *  - Match URL
                 *  - Class name, "as is"
                 *  - Method name, "as is"
                 *  - Class name, backslashes changed to dots, lowercased
                 *  - Method name, lowercased
                 */
                return sprintf(

                    $data['format'],

                    $data['url'], $class, $method,

                    str_replace( '\\', '.', strtolower( $class ) ), strtolower( $method )
                );
            }
        }

        // If no pattern match, let's assume we're dealing with PHP Internal Classes

        return sprintf(

            '<a href="%s/%s.%s">%s::%s()</a>', self::PHP_MANUAL_URL,

            strtolower( $class ), strtolower( $method ), $class, $method
        );
    }

    private function linkifyFunctions( $expression ) {

        // Linking standalone functions

        $functions = get_defined_functions();

        $exp = trim( $expression, '()' );   # No parenthesis, please

        if( in_array( $exp, $functions['internal'] ) ) {

            return sprintf(

                '<a href="%s/%s">%s()</a>',

                self::PHP_MANUAL_URL, $exp, $exp
            );

        }

        return $expression;
    }
}