<?php

/**
 * Application Abstract Class | Application/AbstractApplication.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Application;

use Next\Components\Object;                            # Object Class
use Next\Components\Collections\AbstractCollection;    # Abstract Collection Class

/**
 * Defines a \Next\Components\Collections\AbstractCollection for Applications.
 * To be a valid within this Collection, the Object must implement the
 * \Next\Application\Application Interface
 *
 * @package    Next\Application
 */
class Chain extends AbstractCollection {

    /**
     * Check Object acceptance
     *
     * Check if given Object is acceptable in Application Chain
     * To be valid, the Object must implement \Next\Application\Application
     *
     * @param \Next\Components\Object $object
     *  An Object object
     *
     *  The checking for \Next\Application\Application implementation
     *  will be done inside the method.
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Application Collection and FALSE otherwise
     *
     * @throws \Next\Application\ApplicationException
     *  Given application is not acceptable in Application Chain
     */
    protected function accept( Object $object ) {

        if( ! $object instanceof Application ) {
            throw ApplicationException::invalidApplication( $object );
        }

        return TRUE;
    }
}
