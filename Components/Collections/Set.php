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
 * Defines a variation of \Next\Components\Collections\Lists that
 * doesn't accept duplicated \Next\Components\Object
 *
 * @package    Next\Components\Collections
 */
class Set extends Lists {

    /**
     * Check Object acceptance
     *
     * @param \Next\Components\Object $object
     *  Object to test before add to Collection
     *
     * @return boolean
     *  TRUE if given Object is not present in Set Collection and FALSE otherwise
     */
    protected function accept( Object $object ) {

        return ( ! $this -> contains( $object ) );
    }
}
