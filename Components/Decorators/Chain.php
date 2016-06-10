<?php

namespace Next\Components\Decorators;

use Next\Components\Object;              # Object Class
use Next\Components\Collections\Set;     # Collection Set Class

class Chain extends Set {

    // Method Override

    /**
     * Check Object acceptance
     *
     * @param Next\Components\Object $object
     *  Object to test before add to Collection
     *
     * @return boolean
     *  TRUE if given Object is not present in Set Collection and FALSE otherwise
     *
     * @throws Next\Components\Decorators\DecoratorException
     *  Given decorator is not acceptable in Decorators Chain
     */
    protected function accept( Object $object ) {

        if( parent::accept( $object ) ) {

            if( ! $object instanceof Decorator ) {

                throw DecoratorException::invalidDecorator( $object );
            }

            return TRUE;
        }

        return FALSE;
    }
}