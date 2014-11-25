<?php

namespace Next\DB\Entity;

use Next\DB\Query\Query;        # Query Interface

use Next\Components\Object;     # Object Class
use Next\Components\Invoker;    # Invoker Class

use Next\DB\Table\Manager;      # Table Manager Class

class Repository extends Object {

    /**
     * Table Manager
     *
     * @var Next\DB\Table\Manager
     */
    protected $manager;

    /**
     * Table Name
     * Not to confuse with Next\DB\Table\Table Object
     *
     * @var string
     */
    protected $table;

    /**
     * Entity Repository Constructor
     *
     * @param Next\DB\Table\Manager $manager
     *  Table Manager
     */
    public function __construct( Manager $manager ) {

        $this -> manager = $manager;

        /**
         * @internal
         * Invoking the parent constructor must occur after set the property above
         * in order to trigger Object::init() from child classes which may change
         * the Table Object used by Manager
         */
        parent::__construct();

        // And this integrity checking ensures a Table Object was set

        $this -> checkIntegrity();

        $this -> table   = $this -> manager -> getTable() -> getTable();

        // Extend Object Context to Entity Manager

        $this -> extend( new Invoker( $this, $this -> manager ) );
    }

    /**
     * Finds an Entity by its Primary Key / Unique Identifier
     *
     * @param  array $id
     *  Primary Key / Unique Identifier of the Entity being looked for
     *
     * @return Next\DB\Table\RowSet
     *  RowSet Object with Entity data, if any
     *
     * @see Next\DB\Statement\Statement::fetch()
     */
    public function find( array $id ) {

        $this -> manager -> select()
                         -> from( array( $this -> table ) )
                         -> where( sprintf( '%1$s = :%1$s', key( $id ) ), $id );

        return $this -> manager -> fetch();
    }

    /**
     * Finds all Entities in the Repository
     *
     * @param Next\DB\Query\Expression|string|array|optional $columns
     *  Columns to be included in SELECT Statement
     *
     * @return Next\DB\Table\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @see Next\DB\Statement\Statement::fetchAll()
     */
    public function findAll( $columns = Query::WILDCARD ) {

        $this -> manager -> select( $columns ) -> from( $this -> table );

        return $this -> manager -> fetchAll();
    }

    /**
     * Finds Entities in the Repository that matches a set of criteria
     *
     * @param  array  $criteria
     *  One or more criteria to condition the Entity
     *
     * @param  string|array|optional  $order
     *  One or more fields to order the Entity in the resultset
     *
     * @param  string|array|optional  $limit
     *  Restrictions on how many Entities will be present in the resultset and
     *  from which offset the finding process will start
     *
     * @return Next\DB\Table\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @see Next\DB\Statement\Statement::fetchAll()
     */
    public function findBy( array $criteria, $order = NULL, $limit = NULL ) {

        $this -> manager -> select() -> from( $this -> table );

        // WHERE Conditions

        foreach( array_keys( $criteria ) as $column ) {
            $this -> manager -> where( sprintf( '%1$s = :%1$s', $column ), $criteria );
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

    // Auxiliary Methods

    /**
     * Checks the basic integrity of Repository Class
     *
     * @return void
     *
     * @throws Next\DB\Entity\EntityException
     *  Thrown if no Table Object was defined
     */
    private function checkIntegrity() {

        if( is_null( $this -> manager -> getTable() ) ) {
            throw EntityException::noTableObject();
        }
    }
}