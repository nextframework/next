<?php

/**
 * Controllers Chain Class | Controller\Chain.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Object;                    # Object Class
use Next\Components\Collections\Collection;    # Abstract Collection Class

/**
 * A Collection for Controller Objects
 *
 * @package    Next\Controller
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Object
 *             Next\Components\Collections\Collection
 */
class Chain extends Collection {

    /**
     * Checks if given `Next\Components\Object` is acceptable in a
     * Controllers' Chain
     *
     * To be valid, the Object must implement Next\Controller\Controller` Interface
     *
     * @param \Next\Components\Object $object
     *  An Object object
     *
     * @return boolean
     *  TRUE if given Object is acceptable in Controllers' Collection
     *  and FALSE otherwise
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Given Object is not acceptable in a Controllers' Chain
     */
    public function accept( Object $object ) : bool {

        if( ! $object instanceof Controller ) {

            throw new InvalidArgumentException(

                sprintf(

                    '<strong>%s</strong> is not a valid Controller

                    Controllers must implement <em>Next\Controller\Controller</em> Interface',

                    $object
                )
            );
        }

        return TRUE;
    }
}
