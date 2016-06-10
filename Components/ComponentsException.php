<?php

namespace Next\Components;

/**
 * Components Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ComponentsException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x000001CB, 0x000001FD );

    /**
     * Constructor Overwritten
     *
     * @var integer
     */
    const CONSTRUCTOR_OVERWRITTEN = 0x000001CB;

    /**
     * Constructor of Object class has been overwritten and thus the extended
     * context and/or function prototyping feature were nullified
     *
     * @param string $method
     *  Method being invoked

     * @param Next\Components\Object $object
     *  The Object context
     *
     * @return Next\Components\ComponentsException
     *  Exception for constructor overwritten
     */
    public static function extendedContextFailure( $method, Object $object ) {

        return new self(

            'Method <strong>%s</strong> is not known by <strong>%s</strong>
            and was not trapped by <em>Object::__call()</em>

            Could you possibly overwrote <em>Object::__construct()</em>
            without invoke it under parent context?',

            self::CONSTRUCTOR_OVERWRITTEN,

            array( $method, $object )
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
     * @param Next\Components\Object $object
     *  The Object context
     *
     * @return Next\Components\ComponentsException
     *  Exception for constructor overwritten
     */
    public static function overloadedPropertyUpdateFailure( $property, Object $object ) {

        return new self(

            'Property <strong>%s</strong> is not known by <strong>%s</strong>
            and was not trapped by <em>Object::__set()</em>

            Could you possibly overwrote <em>Object::__construct()</em>
            without invoke it under parent context?',

            self::CONSTRUCTOR_OVERWRITTEN,

            array( $property, $object )
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
     * @param Next\Components\Object $object
     *  The Object context
     *
     * @return Next\Components\ComponentsException
     *  Exception for constructor overwritten
     */
    public static function overloadedPropertyReadingFailure( $property, Object $object ) {

        return new self(

            'Property <strong>%s</strong> is not known by <strong>%s</strong>
            and was not trapped by <em>Object::__get()</em>

            Could you possibly overwrote <em>Object::__construct()</em>
            without invoke it under parent context?',

            self::CONSTRUCTOR_OVERWRITTEN,

            array( $property, $object )
        );
    }
}