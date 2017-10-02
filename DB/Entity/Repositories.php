<?php

/**
 * Database Entity Repositories Collection Class | DB\Entity\Repositories.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Entity;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;
use Next\Exception\Exceptions\BadMethodCallException;
use Next\Exception\Exceptions\DomainException;

use Next\Components\Object;    # Object Class
use Next\DB\Table\Manager;     # Entities Manager Class

/**
 * Entities Repository Collection Class
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2016 Next Studios
 *
 * @package      Next
 * @subpackage   DB\Entity
 *
 * @uses         ReflectionClass,
 *               ReflectionException,
 *               \Next\Components\Object,
 *               \Next\DB\Table\Manager
 *               \Next\DB\Entity\EntityException
 */
class Repositories extends Object {

    /**
     * Repositories Collection
     *
     * @var array $repositories
     */
    private $repositories = [];

    /**
     * Add a new Entity Repository
     *
     * @param string|object $repository
     *  Repository classname or object
     *
     * @param string|optional $alias
     *  An optional alias for the Repository
     *
     * @param \Next\DB\Table\Manager $manager
     *  Entity Manager Object
     *
     * @return \Next\DB\Entity\Repositories
     *  Repositories Object (Fluent-Interface)
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if FQCN of Repository is not an instance of `Next\DB\Entity\Repository`
     *
     * @throws \Next\Exception\Exceptions\BadMethodCallException
     *  Thrown if the resulting Repository from given FQCN couldn't
     *  be handled raising a \ReflectionException
     */
    public function addRepository( $repository, $alias = NULL, Manager $manager ) {

        try {

            $reflector = new \ReflectionClass( $repository );

            if( ! $reflector -> isSubclassOf( 'Next\DB\Entity\Repository' ) ) {

                throw new InvalidArgumentException(

                    sprintf(

                        '<strong>%s</strong> is not a valid Repository

                        Repositories must be an instance of <em>Next\\DB\Entity\Repository</em>',

                        $repository
                    )
                );
            }

            $alias = ( ! is_null( $alias ) ? trim( $alias ) : $reflector -> getShortName() );

            $this -> repositories[ $alias ] = $reflector -> newInstance( $manager );

        } catch( \ReflectionException $e ) {

            /**
             * @internal
             *
             * Because PHP's ReflectionClass doesn't take imported namespaces
             * as valid arguments for ReflectionClass::isSubclassOf(), the attempt
             * of creating a valid ReflectionClass object must be
             * properly handled within a try...catch() block because if this
             * ReflectionException is not caught here, it'll, eventually, be
             * handled by the Framework itself in \Next\Controller\Front::dispatch()
             *
             * However, because that handling has a different purpose,
             * Repository Classes that can't be found would return a not-so-useful
             * Response, making it difficult to debug
             */
            throw new BadMethodCallException( $e -> getMessage() );
        }

        return $this;
    }

    /**
     * Get an Entity Repository
     *
     * @param string $repository
     *  Entity Repository to retrieve, be it the FQCN or its alias directly
     *
     * @return \Next\DB\Entity\Repository
     *  The Entity Repository Object
     *
     * @throws \Next\Exception\Exceptions\DomainException
     *  Thrown if Repository Object doesn't exist
     */
    public function getRepository( $repository ) {

        if( strpos( $repository, '\\' ) !== FALSE ) {
            $repository = implode( '', array_slice( explode( '\\', $repository ), -1 ) );
        }

        if( ! array_key_exists( $repository, $this -> repositories ) ) {

            throw new DomainException(

                sprintf(

                    'Repository <strong>%s</strong> doesn\'t exist',

                    $repository
                )
            );
        }

        return $this -> repositories[ $repository ];
    }
}
