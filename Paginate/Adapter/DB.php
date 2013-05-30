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
     *   Data Source
     */
    public function __construct( Manager $em ) {

        $this -> em =& $em;
    }

    // Adapter Interface Method Implementation

    /**
     * Get items from given offset
     *
     * @param integer $offset
     *   Offset to start the range
     *
     * @param integer $itemsPerPage
     *   Number of Items per Page
     *
     * @return array
     *   Range of pages
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
     *   Number of elements present in given source
     *
     * @TODO Optimize to not query database twice
     */
    public function count() {
        return count( $this -> em -> fetchAll() );
    }
}