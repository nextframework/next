<?php

namespace Next\Paginate\Adapter;

use Next\DB\Query\Query;
use Next\DB\Query\Expression;
use Next\DB\Table\Manager;
use Next\DB\Table\Select;

/**
 * DB Paginate Adapter Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class DB implements Adapter {

    /**
     * Adapter Data Source
     *
     * @var array $source
     */
    private $source = array();

    /**
     * Table Manager Object
     *
     * @var Next\DB\Table\Manager $em
     */
    private $em;

    /**
     * DB Adapter Constructor
     *
     * @param array $source
     *  Data Source
     */
    public function __construct( Manager $em ) {

        $this -> em =& $em;
    }

    // Adapter Interface Method Implementation

    /**
     * Get items from given offset
     *
     * @param integer $offset
     *  Offset to start the range
     *
     * @param integer $itemsPerPage
     *  Number of Items per Page
     *
     * @return array
     *  Range of pages
     */
    public function getItems( $offset, $itemsPerPage ) {

        $this -> em -> resetPart( QUERY::SQL_LIMIT )
                    -> limit( $itemsPerPage, $offset );

        $this -> source = $this -> em -> fetchAll();

        return $this -> source;
    }

    // Countable Interface Method Implementation

    /**
     * Count Pagination Data Source
     *
     * @return integer
     *  Number of elements present in given source
     *
     * @TODO Optimize to not query database twice
     */
    public function count() {
        return count( $this -> em -> fetchAll() );

        /**
         * Because Object Bridge still restricted to methods invocation
         * Next\DB\Query\Builder needs to use static properties and because of
         * this the proposal below is not possible because it's overwriting
         * original Query string
         */
        /*if( ! is_null( $this -> count ) ) {
            return $this -> count;
        }

        $em = clone $this -> em;

        $conditions     = $this -> em -> getPart( Query::SQL_WHERE );
        $replacements   = $this -> em -> getReplacements( Query::SQL_WHERE );

        $select = new Manager( $this -> em -> getDriver(), $this -> em -> getTable() );

        $select -> select( new \Next\DB\Query\Expression( 'COUNT(1) as `count`' ) )
                -> from( array( $this -> em -> getTable() -> getTable() ) );

        // Replicating WHERE Clauses

        foreach( $conditions as $condition ) {
            $select -> where( key( $condition ) );
        }

        $select -> setReplacements( $replacements );

        $this -> count = (int) $select -> fetch() -> current() -> count;

        return $this -> count;*/
    }
}