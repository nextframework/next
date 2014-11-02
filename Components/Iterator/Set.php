<?php

namespace Next\Components\Iterator;

use Next\Components\Object;    # Object Class

/**
 * Set Class
 *
 * Set is a List which doesn't accepts duplicated objects
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Set extends Lists {

    /**
     * Check Object acceptance
     *
     * @param Next\Components\Object $object
     *  Object to test before add to Collection
     *
     * @return boolean
     *  TRUE if given Object is not present in Set Collection and FALSE otherwise
     */
    protected function accept( Object $object ) {

        return ( ! $this -> contains( $object ) );
    }
}
