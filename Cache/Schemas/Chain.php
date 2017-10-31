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
use Next\Components\Collections\Collection;    # Abstract Collection Class

/**
 * A Collection for Caching Schemas
 *
 * @package    Next\Cache
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Object
 *             Next\Components\Collections\Collection
 */
class Chain extends Collection {

    /**
     * Checks if given `Next\Components\Object` is acceptable in a
     * Caching Schemas' Chain
     *
     * To be valid, the Object must implement Next\Cache\Schemas\Schema` Interface
     *
     * @param \Next\Components\Object $object
     *  An Object object
     *
     * @return boolean
     *  TRUE if given Object is acceptable in Caching Schemas' Collection.
     *  If not an Next\Exception\Exceptions\InvalidArgumentException
     *  will be thrown instead
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Given Object is not acceptable in a Caching Schemas' Chain
     */
    public function accept( Object $object ) : bool {

        if( ! $object instanceof Schema ) {

            throw new InvalidArgumentException(

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
