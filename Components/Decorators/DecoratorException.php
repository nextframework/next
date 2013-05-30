<?php

namespace Next\Components\Decorators;

use Next\Components\Object;    # Object Class

/**
 * Decorator Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class DecoratorException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x000008C4, 0x000008F6 );

    /**
     * Invalid Decorator
     *
     * <p>Used by Next\Components\Decorators\Chain</p>
     *
     * @var integer
     */
    const INVALID_DECORATOR = 0x000008C4;

    // Exceptions Messages

    /**
     * Invalid Decorator
     *
     * Given Object is not a valid Decorator because it doesn't
     * implements Next\Components\Decorators\Decorator
     *
     * @param Next\Components\Object $object
     *   Object assigned as Decorator
     *
     * @return Next\Components\Decorators\DecoratorException
     *   Exception for invalid Decorators
     */
    public static function invalidDecorators( Object $object ) {

        return new self(

            '<strong>%s</strong> is not a valid Decorator.

            <br /><br />

            Decorators must implement Decorator Interface',

            self::INVALID_DECORATOR,

            (string) $object
        );
    }
}