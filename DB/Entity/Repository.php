<?php

/**
 * Database Entity Repository Class | DB\Entity\Repository.php
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

use Next\Components\Interfaces\Verifiable;    # Verifiable Interface
use Next\DB\Query\Query;                      # Query Interface
use Next\Components\Object;                   # Object Class
use Next\Components\Invoker;                  # Invoker Class

/**
 * Entity Repository Base Class
 *
 * @package      Next\DB
 *
 * @uses         \Next\DB\Query\Query, \Next\Components\Object,
 *               \Next\Components\Invoker, \Next\DB\Entity\Manager
 *               \Next\DB\Entity\EntityException
 */
class Repository extends Object implements Verifiable {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'entity' => [ 'type' => 'Next\DB\Entity\Entity', 'required' => FALSE ]
    ];

    /**
     * Finds an Entity by one of its Database Table Columns
     *
     * @param array $condition
     *  An associative array being the Database Table Column to search
     *  the key and the value the condition to match
     *
     * @return \Next\DB\DataGateway\RowSet
     *  RowSet Object with Entity data, if any
     *
     * @see \Next\DB\Statement\Statement::fetch()
     */
    public function find( array $condition ) {

        $this -> verify();

        $this -> select() -> from( $this -> options -> entity -> getEntityName() )
                          -> where( sprintf( '%1$s = :%1$s', key( $condition ) ), $condition );

        return $this -> fetch();
    }

    /**
     * Finds all Entities in the Repository
     *
     * @param \Next\DB\Query\Expression|string|array|optional $columns
     *  Columns to be included in SELECT Statement
     *
     * @return \Next\DB\DataGateway\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @see \Next\DB\Statement\Statement::fetchAll()
     */
    public function findAll( $columns = Query::WILDCARD ) {

        $this -> verify();

        $this -> select( $columns ) -> from( $this -> options -> entity -> getEntityName() );

        return $this -> fetchAll();
    }

    /**
     * Finds Entities in the Repository that match a set of criteria,
     * optionally limiting the number of records on the resulting
     * RowSet and with a custom ordering Clause
     *
     * ````
     * # Find data by User ID — PRIMARY KEY `user` — restricted by
     * # IDs '1' and '2' in descendant order
     *
     * $manager -> findBy(
     *     [ [ 'user = :user', [ 'user' => [ 1, 2 ] ] ] ], [ 'user' => Query::ORDER_DESCENDING ]
     * );
     * ````
     *
     * @param array $conditions
     *  One or more conditions to condition the Entity
     *
     * @param string|array|optional $order
     *  One or more fields to order the Entity in the resultset
     *
     * @param string|array|optional $limit
     *  Restrictions on how many Entities will be present in the resultset and
     *  from which offset the finding process will start
     *
     * @return \Next\DB\DataGateway\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @see \Next\DB\Statement\Statement::fetchAll()
     */
    public function findBy( array $conditions, array $order = [], $limit = NULL ) {

        $this -> verify();

        $statement = $this -> select() -> from(
                        $this -> options -> entity -> getEntityName()
                     );

        // WHERE Conditions

        foreach( $conditions as $condition ) {

            list( $criteria, $replacements, $type ) = $condition + [ NULL, [], Query::SQL_OR ];

            $statement -> where( $criteria, $replacements, $type );
        }

        // ORDER Clause(s)

        if( $order !== NULL ) $statement -> order( $order );

        // LIMIT Clause

        if( $limit !== NULL ) {

            list( $count, $offset ) = (array) $limit + [ NULL, NULL ];

            $statement -> limit( $count, $offset );
        }

        return $this -> fetchAll();
    }

    /**
     * Finds one Entity in the Repository that matches a set of criteria
     *
     * It's an alias for Repository::findBy()
     *
     * @param array $condition
     *  One or more condition to condition the Entity
     *
     * @param string|array|optional $order
     *  One or more fields to order the Entity in the resultset
     *
     * @return \Next\DB\DataGateway\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @see \Next\DB\Entity\Repository::findBy()
     */
    public function findOneBy( array $condition, $order = NULL ) {
        $this -> findBy( $condition, $order, 1 );
    }

    // Accessory Methods

    /**
     * Get Entity Object associated to this Repository
     *
     * @return \Next\DB\Entity\Entity
     *  Entity Object
     */
    public function getEntity() {
        return $this -> options -> entity;
    }

    // Auxiliary Methods

    /**
     * Verifies Object Integrity
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if there's no \Next\DB\Entity\Entity Object defined
     */
    public function verify() {

        if( $this -> options -> entity === NULL ) {

            throw new InvalidArgumentException(
                'Repository Objects requires an Object instance of
                <em>Next\DB\Entity\Entity</em> to be operated by the
                <em>Next\DB\Entity\Manager</em>'
            );
        }
    }
}