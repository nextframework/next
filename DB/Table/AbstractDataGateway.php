<?php

namespace Next\DB\Table;

use Next\DB\Query\Query;          # Query Interface
use Next\Components\Object;       # Object Class

/**
 * DataGateway Abstract Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractDataGateway extends Object implements DataGateway {

    /**
     * Table Manager Object
     *
     * @var Next\DB\Table\Manager $manager
     */
    protected $manager;

    /**
     * Data Source, from fetching processes
     *
     * @var array $source
     */
    protected $source = array();

    /**
     * Data Gateway Constructor.
     *
     * Set Table Manager object and Data Source obtained from fetching processes
     *
     * @param Next\DB\Table\Manager $manager
     *  Table Manager Object
     *
     * @param mixed $source
     *  Source Data to populate the Row/Rowset
     *
     * @see Next\DB\Table\AbstractDataGateway::setSource()
     */
    public function __construct( Manager $manager, $source ) {

        $this -> manager =& $manager;

        // Setting Up the Source Data

        $this -> setSource( $source );

        parent::__construct();
    }

    // Countable Interface Method Implementation

    /**
     * Count elements of Data Source
     *
     * @return integer
     *  Number of elements in RowSet
     */
    public function count() {
        return count( $this -> source );
    }

    // Abstract Method Definition

    /**
     * Set Data Source
     *
     * @param mixed|array $source
     *  Source Data
     */
    protected function setSource( $source ) {
        $this -> source = $source;
    }

    // Accessors

    /**
     * Get DataSource
     *
     * @return array
     *  Data Source
     */
    public function getSource() {
        return ( count( $this -> source ) == 1 ? $this -> source[ 0 ] : $this -> source );
    }

    /**
     * Get Table Manager
     *
     * @return Next\DB\Table\Manager
     *  Table Manager Object
     */
    public function getManager() {
        return $this -> manager;
    }
}