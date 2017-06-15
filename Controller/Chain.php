<?php

/**
 * Controllers Chain Class | Controller\Chain.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Controller;

use Next\Components\Object;                            # Object Class
use Next\Components\Collections\AbstractCollection;    # Abstract Collection Class

/**
 * Defines a \Next\Components\Collections\AbstractCollection for Controllers.
 * To be a valid within this Collection, the Object must implement the
 * \Next\Controller\Controller Interface
 *
 * @package    Next\Controller
 */
class Chain extends AbstractCollection {

    /**
     * Check Object acceptance
     *
     * Check if given Controller is acceptable in Controllers Chain
     * To be valid, the Controller must implement \Next\Controller\Controller Interface
     *
     * @param \Next\Components\Object $object
     *  An Object object
     *
     *  The checking for \Next\Controller\Controller Interface will be inside
     *  the method.
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Controllers Collection and FALSE otherwise
     *
     * @throws \Next\Controller\ControllerException
     *  Given Controller is not acceptable in the Controller Chain
     */
    public function accept( Object $object ) {

        if( ! $object instanceof Controller ) {

            throw ControllerException::invalidController( $object );
        }

        return TRUE;
    }
}
