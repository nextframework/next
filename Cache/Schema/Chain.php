<?php

namespace Next\Cache\Schema;

use Next\Cache\CacheException;                         # Cache Exception Class

use Next\Components\Object;                            # Object Class
use Next\Components\Collections\AbstractCollection;    # Abstract Collection Class

/**
 * Cache Schema Chain Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2017 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Chain extends AbstractCollection {

    /**
     * Check Object acceptance
     *
     * Check if given Object is acceptable in Caching Schema Chain
     * To be valid, the Object must implement Next\Cache\Schema\Schema
     *
     * @param Next\Components\Object $object
     *  An Object object
     *
     *  The checking for Next\Cache\Schema\Schema implementation
     *  will be done inside the method.
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Caching Schema Collection and FALSE otherwise
     *
     * @throws Next\Cache\CacheException
     *  Given Caching Schema is not acceptable in Caching Schema Chain
     */
    protected function accept( Object $object ) {

        if( ! $object instanceof Schema ) {
            throw CacheException::invalidCachingSchema( $object );
        }

        return TRUE;
    }
}
