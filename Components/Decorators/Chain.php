<?php

/**
 * Decorators Component Chain Class | Components\Decorators\Chain.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Decorators;

use Next\Components\Object;              # Object Class
use Next\Components\Collections\Set;     # Collection Set Class

/**
 * Defines a \Next\Components\Collections\Set for Decorators.
 * To be a valid within this Collection, the Object must implement the
 * \Next\Components\Decorator\Decorator Interface
 *
 * @package    \Next\Components\Decorator
 */
class Chain extends Set {

    // Method Override

    /**
     * Check Object acceptance
     *
     * @param \Next\Components\Object $object
     *  Object to test before add to Collection
     *
     * @return boolean
     *  TRUE if given Object is not present in Set Collection and FALSE otherwise
     *
     * @throws \Next\Components\Decorators\DecoratorException
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