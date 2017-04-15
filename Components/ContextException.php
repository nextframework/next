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
class ContextException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x000001FE, 0x00000230 );

    /**
     * Resource is not mimic-able
     *
     * @var integer
     */
    const NOT_MIMICABLE = 0x000001FE;

    /**
     * Caller Object not found as extended context
     *
     * @var integer
     */
    const CALLER_NOT_FOUND = 0x000001FF;

    /**
     * Method not known in extended context
     *
     * @var integer
     */
    const METHOD_NOT_FOUND = 0x00000200;

    /**
     * Property not known in extended context
     *
     * @var integer
     */
    const PROPERTY_NOT_FOUND = 0x00000201;

    /**
     * Property changes failed
     *
     * @var integer
     */
    const PROPERTY_CHANGES_FAILED = 0x00000202;

    /**
     * Resource not mimic-able
     *
     * @param mixed $resource
     *  Resource trying to mimic a Next\Components\Object instance
     *
     * @return Next\Components\ComponentsException
     *  Resource not mimic-able
     */
    public static function notMimicable() {

        return new self(

            'Only objects can (or need to) be mimic-able',

            self::NOT_MIMICABLE
        );
    }

    /**
     * Caller Object not found as extended context
     * For debugging purposes only
     *
     * @param string $caller
     *  Object caller name
     *
     * @return Next\Components\ComponentsException
     *  Caller Object not found
     */
    public static function callerNotFound( $caller ) {

        return new self(

            'Object <strong>%s</strong> could not be recognized as a valid extended context',

            self::CALLER_NOT_FOUND,

            array( $caller )
        );
    }

    /**
     * Called method could not be found in the extended context
     *
     * @param string $method
     *  Method being called
     *
     * @return Next\Components\ComponentsException
     *  Method not found
     */
    public static function methodNotFound( $method ) {

        return new self(

            'Method <strong>%s</strong> could not be matched against any
            methods in extended Context',

            self::METHOD_NOT_FOUND,

            array( $method )
        );
    }

    /**
     * Property could not be found in extended context
     *
     * @param string $property
     *  Property name
     *
     * @param string $caller
     *  Object caller name
     *
     * @return Next\Components\ComponentsException
     *  Property not found
     */
    public static function propertyNotFound( $property, $caller ) {

        return new self(

            'Property <strong>%s</strong> could not be matched against any
            properties available for extended Context <strong>%s</strong>',

            self::PROPERTY_NOT_FOUND,

            array( $property, $caller )
        );
    }

    /**
     * Caller Object not found as extended context
     * For debugging purposes only
     *
     * @param string $property
     *  Object caller name
     *
     * @param string $caller
     *  Object caller name
     *
     * @param boolean $isAccess
     *  A support argument to reuse some text. Defaults to TRUE
     *
     *  If TRUE then the sentence will refer on property access.
     *  If FALSE then the sentence will refer on property change.
     *
     * @return Next\Components\ComponentsException
     *  Unable to access/modify property
     */
    public static function propertyFailure( $property, $caller, $isAccess = TRUE ) {

        return new self(

            '<p>
                Property <strong>%s</strong> could not be %s from the extended context of <strong>%s</strong>
            </p>

            <p>
                Could the visibility of this property be different of PROTECTED?
            </p>',

            self::PROPERTY_CHANGES_FAILED,

            array( $property, ( $isAccess ? 'accessed' : 'set' ), $caller )
        );
    }
}