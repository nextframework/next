<?php

/**
 * Collection Component Set Class | Components\Collection\Set.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Collections;

use Next\Components\Object;    # Object Class

/**
 * A variation of of Objects' List Collection that doesn't accept
 * duplicated Objects
 *
 * @package    Next\Components\Collections
 *
 * @uses       Next\Components\Object
 *             Next\Components\Collections\Lists
 */
class Set extends Lists {

    /**
     * Check Object acceptance
     *
     * @param \Next\Components\Object $object
     *  Object to have its acceptance in Collection checked
     *
     * @return boolean
     *  TRUE if given Object is not already present in Set Collectio
     *  and FALSE otherwise
     */
    protected function accept( Object $object ) : bool {
        return ( ! $this -> contains( $object ) );
    }
}
