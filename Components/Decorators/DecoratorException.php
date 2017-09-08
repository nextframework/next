<?php

/**
 * Decorator Exception Class | Decorators\DecoratorException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
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