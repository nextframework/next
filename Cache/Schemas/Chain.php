<?php

/**
 * Caching Schema Chain Class | Cache\Schema\Chain.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Cache\Schemas;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Object;                            # Object Class
use Next\Components\Collections\AbstractCollection;    # Abstract Collection Class

/**
 * Defines a \Next\Components\Collections\AbstractCollection for Caching Schemas.
 *
 * @package    Next\Cache
 */
class Chain extends AbstractCollection {

    /**
     * Checks if given `Next\Components\Object` is acceptable in a
     * Caching Schemas' Chain
     *
     * To be valid, the Object must implement `\Next\Cache\Schemas\Schema` Interface
     *
     * @param \Next\Components\Object $object
     *  An Object object
     *
     * @return boolean
     *  TRUE if given Object is acceptable in Caching Schemas' Collection
     *  and FALSE otherwise
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Given Object is not acceptable in a Caching Schemas' Chain
     */
    public function accept( Object $object ) {

        if( ! $object instanceof Schema ) {

            return new InvalidArgumentException(

                sprintf(

                    '<strong>%s</strong> is not a valid Caching Schema

                    Caching Schemas must implement <em>Next\Cache\Schemas\Schema</em> Interface',

                    $object
                )
            );
        }

        return TRUE;
    }
}
