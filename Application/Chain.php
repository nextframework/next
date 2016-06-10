<?php

namespace Next\Application;

use Next\Components\Object;                            # Object Class
use Next\Components\Collections\AbstractCollection;    # Abstract Collection Class

/**
 * Application Chain Class
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
     * Check if given Object is acceptable in Application Chain
     * To be valid, the Object must implement Next\Application\Application
     *
     * @param Next\Components\Object $object
     *  An Object object
     *
     *  The checking for Next\Application\Application implementation
     *  will be done inside the method.
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Application Collection and FALSE otherwise
     *
     * @throws Next\Application\ApplicationException
     *  Given application is not acceptable in Application Chain
     */
    protected function accept( Object $object ) {

        if( ! $object instanceof Application ) {

            throw ApplicationException::invalidApplication( $object );
        }

        return TRUE;
    }
}
