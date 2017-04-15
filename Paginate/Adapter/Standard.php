<?php

namespace Next\Paginate\Adapter;

use Next\Components\Object;    # Object Class

/**
 * Standard Paginate Adapter Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Standard extends Object implements Adapter {

    /**
     * Adapter Data Source
     *
     * @var array $source
     */
    private $source = array();

    /**
     * Standard Adapter Constructor
     *
     * @param array $source
     *  Data Source
     *
     * @param mixed|Next\Components\Object|Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for Paginate Standard Adapter
     */
    public function __construct( array $source, $options = NULL ) {

        parent::__construct( $options );

        $this -> source =& $source;
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
        return array_slice( $this -> source, $offset, $itemsPerPage );
    }

    // Countable Interface Method Implementation

    /**
     * Count Pagination Data Source
     *
     * @return integer
     *  Number of elements present in given source
     */
    public function count() {
        return count( $this -> source );
    }
}