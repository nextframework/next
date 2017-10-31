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
 * Exception Classes
 */
use Next\Exception\Exception;
use Next\Exception\Exceptions\FatalException;

/**
 * Wrapper static methods for all Exceptions thrown within the Components Module
 *
 * @package    Next\Components
 *
 * @uses       Next\Exception\Exception
 *             Next\Exception\Exceptions\FatalException
 */
class ComponentsException extends Exception {

    /**
     * Constructor of Object class has been overwritten and thus the
     * extended context and/or function prototyping feature were nullified
     *
     * @param \Next\Components\Object $object
     *  The Object context
     *
     * @param string|optional $method
     *  Method being invoked
     *
     * @return \Next\Exception\Exceptions\FatalException
     *  Exception for constructor overwritten
     */
    public static function extendedContextFailure( Object $object, $method = NULL ) : FatalException {

        if( $method !== NULL ) {

            return new FatalException(

                sprintf(

                    'Method <strong>%s</strong> not known by
                    <strong>%s</strong> and was not trapped by
                    <em>Object::__call()</em>

                    Could you possibly overwrote <em>Object::__construct()</em>
                    without invoke it under parent context?',

                    $method, $object
                )
            );
        }

        return new FatalException(

            sprintf(

                'Unable to Extend Context of <strong>%s</strong>

                Could you possibly overwrote <em>Object::__construct()</em>
                without invoke it under parent context?',

                $object
            )
        );
    }
}