<?php

/**
 * Caching Schema Chain Class | Cache\Schema\Chain.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Cache\Schema;

use Next\Cache\CacheException;                         # Cache Exception Class

use Next\Components\Object;                            # Object Class
use Next\Components\Collections\AbstractCollection;    # Abstract Collection Class

/**
 * Defines a \Next\Components\Collections\AbstractCollection for Caching Schemas.
 * To be a valid within this Collection, the Object must implement the
 * \Next\Cache\Schema\Schema Interface
 *
 * @package    Next\Cache\Schema
 */
class Chain extends AbstractCollection {

    /**
     * Check Object acceptance
     *
     * Check if given Object is acceptable in Caching Schema Chain
     * To be valid, the Object must implement \Next\Cache\Schema\Schema
     *
     * @param \Next\Components\Object $object
     *  An Object object
     *
     *  The checking for \Next\Cache\Schema\Schema implementation
     *  will be done inside the method.
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Caching Schema Collection and FALSE otherwise
     *
     * @throws \Next\Cache\CacheException
     *  Given Caching Schema is not acceptable in Caching Schema Chain
     */
    protected function accept( Object $object ) {

        if( ! $object instanceof Schema ) {
            throw CacheException::invalidCachingSchema( $object );
        }

        return TRUE;
    }
}
