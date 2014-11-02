<?php

namespace Next\Controller;

use Next\Components\Object;                         # Object Class
use Next\Components\Iterator\AbstractCollection;    # Abstract Collection Class

/**
 * Controller Chain Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Chain extends AbstractCollection {

    /**
     * Check Object acceptance
     *
     * Check if given Controller is acceptable in Controllers Chain
     * To be valid, the Controller must implement Next\Controller\Controller Interface
     *
     * @param Next\Components\Object $object
     *  An Object object
     *
     *  The checking for Next\Controller\Controller Interface will be inside
     *  the method.
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Controllers Collection and FALSE otherwise
     *
     * @throws Next\Controller\ControllerException
     *  Given application is not acceptable in the Controller Chain
     */
    public function accept( Object $object ) {

        if( ! $object instanceof Controller ) {

            throw ControllerException::invalidController( $object );
        }

        return TRUE;
    }
}
