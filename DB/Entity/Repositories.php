<?php

/**
 * Database Entity Repositories Collection Class | DB\Entity\Repositories.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\DB\Entity;

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
    private $repositories = array();

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
     */
    public function addRepository( $repository, $alias = NULL, Manager $manager ) {

        try {

            $reflector = new \ReflectionClass( $repository );

            if( ! $reflector -> isSubclassOf( 'Next\DB\Entity\Repository' ) ) {
                throw EntityException::invalidRepository( $repository );
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
            throw EntityException::repositoryNotExists( $repository );
        }

        return $this;
    }

    /**
     * Get an Entity Repository
     *
     * @param string $repository
     *  Entity Repository to retrieve, be it the full classpath or its alias directly
     *
     * @return \Next\DB\Entity\Repository
     *  The Entity Repository Object
     *
     * @throws \Next\DB\Entity\EntityException
     *  Thrown if Repository Object doesn't exist
     */
    public function getRepository( $repository ) {

        if( strpos( $repository, '\\' ) !== FALSE ) {
            $repository = implode( '', array_slice( explode( '\\', $repository ), -1 ) );
        }

        if( ! array_key_exists( $repository, $this -> repositories ) ) {
            throw EntityException::repositoryNotExists( $repository );
        }

        return $this -> repositories[ $repository ];
    }
}
