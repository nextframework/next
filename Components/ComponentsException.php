<?php

/**
 * Components Exception Class | Components\ComponentsException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components;

/**
 * Defines wrapper static methods for all Exceptions thrown
 * within the Components Module
 *
 * @package    Next\Components
 */
class ComponentsException extends \Next\Components\Debug\Exception {

    /**
     * Array to Parameter Object Mapping
     *
     * @var integer
     */
    const MAPPING = 0x000001CB;

    /**
     * Constructor Overwritten
     *
     * @var integer
     */
    const CONSTRUCTOR_OVERWRITTEN = 0x000001CC;

    /**
     * Exception for when something unexpected occurs while mapping
     * an array into a \Next\Components\Parameter Object recursively
     *
     * @param string $message
     *  Message with the error occurred
     *
     * @return \Next\Components\ComponentsException
     *  Exception for array to object mapping errors
     */
    public static function mapping( $message ) {
        throw new self( $message, self::MAPPING );
    }

    /**
     * Constructor of Object class has been overwritten and thus the extended
     * context and/or function prototyping feature were nullified
     *
     * @param string $method
     *  Method being invoked

     * @param \Next\Components\Object $object
     *  The Object context
     *
     * @return \Next\Components\ComponentsException
     *  Exception for constructor overwritten
     */
    public static function extendedContextFailure( $method, Object $object ) {

        return new self(

            'Method %s not known by <strong>%s</strong>
            and was not trapped by <em>Object::__call()</em>

            Could you possibly overwrote <em>Object::__construct()</em>
            without invoke it under parent context?',

            self::CONSTRUCTOR_OVERWRITTEN,

            [ ( ! empty( $method ) ? sprintf( '<strong>%s</strong>' ) : '' ), $object ]
        );
    }

    /**
     * Constructor of Object class has been overwritten and thus the extended
     * context and/or function prototyping feature were nullified
     *
     * It's basically the same as ComponentsExecption::extendedContextFailure(),
     * specifically when trying to make use of __set()
     *
     * @param string $property
     *  Property being overloaded
     *
     * @param \Next\Components\Object $object
     *  The Object context
     *
     * @return \Next\Components\ComponentsException
     *  Exception for constructor overwritten
     */
    public static function overloadedPropertyUpdateFailure( $property, Object $object ) {

        return new self(

            'Property <strong>%s</strong> is not known by <strong>%s</strong>
            and was not trapped by <em>Object::__set()</em>

            Could you possibly overwrote <em>Object::__construct()</em>
            without invoke it under parent context?',

            self::CONSTRUCTOR_OVERWRITTEN, [ $property, $object ]
        );
    }

    /**
     * Constructor of Object class has been overwritten and thus the extended
     * context and/or function prototyping feature were nullified
     *
     * It's basically the same as ComponentsExecption::extendedContextFailure(),
     * specifically when trying to make use of __get()
     *
     * @param string $property
     *  Property being overloaded
     *
     * @param \Next\Components\Object $object
     *  The Object context
     *
     * @return \Next\Components\ComponentsException
     *  Exception for constructor overwritten
     */
    public static function overloadedPropertyReadingFailure( $property, Object $object ) {

        return new self(

            'Property <strong>%s</strong> is not known by <strong>%s</strong>
            and was not trapped by <em>Object::__get()</em>

            Could you possibly overwrote <em>Object::__construct()</em>
            without invoke it under parent context?',

            self::CONSTRUCTOR_OVERWRITTEN, [ $property, $object ]
        );
    }
}