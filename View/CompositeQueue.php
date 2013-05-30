<?php

namespace Next\View;

use Next\Components\Object;            # Object Class
use Next\Components\Iterator\Lists;    # Lists Collection Class
/**
 * Composite View Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class CompositeQueue extends Lists {

    /**
     * Check if given argument is acceptable as a Composite Views
     *
     * Check if given Object is acceptable in Composite Views Lists
     * To be valid, the Object must implement Next\View\View Interface
     *
     * @param Next\Components\Object $object
     *   An Object object
     *
     *   The checking for Next\View\View Interface
     *   will be done inside the method.
     *
     * @return boolean
     *   Always TRUE, because if given value is not a valid Composite View
     *   an Exception will be thrown
     *
     * @throws Next\View\ViewException
     *   Given argument is not acceptable as a Composite View
     */
    public function accept( Object $object ) {

        /**
         * @internal
         * Checking Object Type
         *
         * Partial Views must implement View Interface
         */
        if( ! $object instanceof View ) {

            throw ViewException::invalidPartial( $object );
        }

        return TRUE;
    }
}