<?php

/**
 * Decorator Exception Class | Decorators\DecoratorException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Decorators;

use Next\Components\Object;    # Object Class

/**
 * Defines wrapper static methods for all Exceptions thrown
 * within the Decorators Component Module
 *
 * @package    Next\Components\Decorators
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
     * <p>Used by \Next\Components\Decorators\Chain</p>
     *
     * @var integer
     */
    const INVALID_DECORATOR = 0x000008C4;

    // Exceptions Messages

    /**
     * Invalid Decorator
     *
     * Given Object is not a valid Decorator because it doesn't
     * implements \Next\Components\Decorators\Decorator
     *
     * @param \Next\Components\Object $object
     *  Object assigned as Decorator
     *
     * @return \Next\Components\Decorators\DecoratorException
     *  Exception for invalid Decorators
     */
    public static function invalidDecorator( Object $object ) {

        return new self(

            '<strong>%s</strong> is not a valid Decorator.

            <br /><br />

            Decorators must implement Decorator Interface',

            self::INVALID_DECORATOR,

            (string) $object
        );
    }
}