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

use Next\DB\Query\Query;        # Query Interface

use Next\Components\Object;     # Object Class
use Next\Components\Invoker;    # Invoker Class

use Next\DB\Table\Manager;      # Entities Manager Class

/**
 * Entity Repository Base Class
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2016 Next Studios
 *
 * @package      Next
 * @subpackage   DB\Entity
 *
 * @uses         \Next\DB\Query\Query,
 *               \Next\Components\Object,
 *               \Next\Components\Invoker,
 *               \Next\DB\Table\Manager
 *               \Next\DB\Entity\EntityException
 */
class Repository extends Object {

    /**
     * Table Manager
     *
     * @var \Next\DB\Table\Manager
     */
    protected $manager;

    /**
     * Table Name
     * Not to confuse with \Next\DB\Table\Table Object
     *
     * @var string
     */
    protected $table;

    /**
     * Entity Repository Constructor
     *
     * <p>
     *     If an Entity Manager Object is provided, the Entity Repository will
     *     be configured.
     * </p>
     *
     * @param \Next\DB\Table\Manager|optional $manager
     *  Optional Entity Manager
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for each Entity Repository
     */
    public function __construct( Manager $manager = NULL, $options = NULL ) {

        if( ! is_null( $manager ) ) {

            $this -> manager = $manager;

            /**
             * @internal
             *
             * Invoking the parent constructor must occur after set the property above
             * in order to trigger Object::init() from child classes which may change
             * the Table Object used by Manager
             */
            parent::__construct( $options );

            // And this integrity checking ensures a Table Object was set

            $this -> checkIntegrity();

            $this -> table = $this -> manager -> getTable() -> getTableName();

            // Extend Object Context to Entity Manager

            $this -> extend( new Invoker( $this, $this -> manager ) );
        }
    }

    /**
     * Finds an Entity by its Primary Key / Unique Identifier
     *
     * @param array $id
     *  Primary Key / Unique Identifier of the Entity being looked for
     *
     * @return \Next\DB\Table\RowSet
     *  RowSet Object with Entity data, if any
     *
     * @throws \Next\DB\Entity\EntityException
     *  Throw if trying to use resources from Entity Manager without having one
     *
     * @see \Next\DB\Statement\Statement::fetch()
     */
    public function find( array $id ) {

        if( is_null( $this -> manager ) ) {
            throw EntityException::noEntityManager();
        }

        $this -> manager -> select()
                         -> from( array( $this -> table ) )
                         -> where( sprintf( '%1$s = :%1$s', key( $id ) ), $id );

        return $this -> manager -> fetch();
    }

    /**
     * Finds all Entities in the Repository
     *
     * @param \Next\DB\Query\Expression|string|array|optional $columns
     *  Columns to be included in SELECT Statement
     *
     * @return \Next\DB\Table\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @throws \Next\DB\Entity\EntityException
     *  Throw if trying to use resources from Entity Manager without having one
     *
     * @see \Next\DB\Statement\Statement::fetchAll()
     */
    public function findAll( $columns = Query::WILDCARD ) {

        if( is_null( $this -> manager ) ) {
            throw EntityException::noEntityManager();
        }

        $this -> manager -> select( $columns ) -> from( $this -> table );

        return $this -> manager -> fetchAll();
    }

    /**
     * Finds Entities in the Repository that matches a set of criteria
     *
     * @param array $criteria
     *  One or more criteria to condition the Entity
     *
     * @param string|array|optional $order
     *  One or more fields to order the Entity in the resultset
     *
     * @param string|array|optional $limit
     *  Restrictions on how many Entities will be present in the resultset and
     *  from which offset the finding process will start
     *
     * @return \Next\DB\Table\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @throws \Next\DB\Entity\EntityException
     *  Throw if trying to use resources from Entity Manager without having one
     *
     * @see \Next\DB\Statement\Statement::fetchAll()
     */
    public function findBy( array $criteria, $order = NULL, $limit = NULL ) {

        if( is_null( $this -> manager ) ) {
            throw EntityException::noEntityManager();
        }

        $this -> manager -> select() -> from( $this -> table );

        // WHERE Conditions

        foreach( $criteria as $condition => $replacement ) {
            $this -> manager -> where( $condition, $replacement );
        }

        // ORDER Clause(s)

        if( ! is_null( $order ) ) $this -> manager -> order( (array) $order );

        // LIMIT Clause

        if( ! is_null( $limit ) ) {

            list( $count, $offset ) = (array) $limit + array( NULL, NULL );

            $this -> manager -> limit( $count, $offset );
        }

        return $this -> manager -> fetchAll();
    }

    /**
     * Finds one Entity in the Repository that matches a set of criteria
     *
     * It's an acting interface alias for Repository::findBy()
     *
     * @param array $criteria
     *  One or more criteria to condition the Entity
     *
     * @param string|array|optional $order
     *  One or more fields to order the Entity in the resultset
     *
     * @return \Next\DB\Table\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @throws \Next\DB\Entity\EntityException
     *  Throw if trying to use resources from Entity Manager without having one
     *
     * @see \Next\DB\Entity\Repository::findBy()
     */
    public function findOneBy( array $criteria, $order = NULL ) {
        return $this -> findBy( $criteria, $order, 1 );
    }

    // Accessors

    /**
     * Get Entity Manager used with the Entity Repository
     *
     * @return \Next\DB\Table\Manager
     *  Table Manager
     */
    public function getManager() {
        return $this -> manager;
    }

    /**
     * Get Entity Table associated to the Entity Manager used
     * with Entity Repository
     *
     * @return \Next\DB\Table\Table
     *  Entity Table
     *
     * @throws \Next\DB\Entity\EntityException
     *  Throw if trying to use resources from Entity Manager without having one
     *
     * @see \Next\DB\Table\Manager::getTable()
     */
    public function getTable() {

        if( is_null( $this -> manager ) ) {
            throw EntityException::noEntityManager();
        }

        return $this -> manager -> getTable();
    }

    // Auxiliary Methods

    /**
     * Checks the basic integrity of Repository Class
     *
     * @return void
     *
     * @throws \Next\DB\Entity\EntityException
     *  Thrown if no Table Object was defined
     */
    private function checkIntegrity() {

        if( is_null( $this -> manager -> getTable() ) ) {
            throw EntityException::noEntity();
        }
    }
}