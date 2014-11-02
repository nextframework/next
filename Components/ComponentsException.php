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
     * @param string $target
     *  Target of the Exception: A called method or an invoked property
     *
     * @param Next\Components\Object $object
     *  The Object context
     *
     * @param string $type
     *  A support argument to reuse some paragraph text. It may be 'method' or 'property' (no quotes)
     *
     * @return Next\Components\ComponentsException
     *  Exception for constructor overwritten
     */
    public static function constructorOverwritten( $target, Object $object, $type ) {

        switch( $type ) {

            case 'method':

                $paragraph = '<p>
                    Method <strong>%s</strong> is not known by
                    <strong>%s</strong> and was not trapped by
                    <em>__call()</em>.
                </p>

                %s';

            break;

            case 'property':

                $paragraph = '<p>
                    Property <strong>%s</strong> is not known by
                    <strong>%s</strong> and was not trapped by
                    <em>__set()</em>.
                </p>

                %s';

            break;
        }

        //------------------

        return new self(

            $paragraph,

            self::CONSTRUCTOR_OVERWRITTEN,

            array(

                $target, $object,

                '<p>
                    Could you possibly overwrote <em>Object::__construct()</em>
                    without invoke it under parent context?
                </p>'
            )
        );
    }
}