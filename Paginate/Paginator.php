<?php

namespace Next\Paginate;

use Next\Paginate\Style\Style;        # Paginate Scrolling Style Interface
use Next\Paginate\Adapter\Adapter;    # Paginate Adapter
use Next\Paginate\Style\Sliding;      # Paginate Default Scrolling Style

/**
 * Paginator Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Paginator implements \Countable, \IteratorAggregate {

    /**
     * Pagination Adapter
     *
     * @var Next\Paginate\Adapter\Adapter $adapter
     */
    private $adapter;

    /**
     * Scrolling Style
     *
     * @var Next\Paginate\Style\Style $style
     */
    private $style;

    private $count;

    // Defaults

    /**
     * Current Page Number
     *
     * Defaults to 1
     *
     * @var integer $currentPage
     */
    private $currentPage = 1;

    /**
     * Items per Page
     *
     * Defaults to 10
     *
     * @var integer $itemsPerPage
     */
    private $itemsPerPage = 10;

    /**
     * Paginator Constructor
     *
     * @param Next\Paginate\Adapter\Adapter $adapter
     *   Paginate Adapter
     *
     * @param Next\Paginate\Style\Style|optional $style
     *   Optional Paginate Scrolling Style Algorithm
     */
    public function __construct( Adapter $adapter, Style $style = NULL ) {

        $this -> adapter =& $adapter;

        $this -> style   = ( ! is_null( $style ) ? $style : new Sliding );
    }

    // Accessors

    /**
     * List Information about Pagination
     *
     * @return stdClass Object
     *   Pagination Information
     */
    public function getInfo() {

        $data = new \stdClass;

        // Range Information

        $data -> range = new \stdClass;

        $range = $this -> style -> buildRange( $this );

        $data -> range -> min    = min( $range );
        $data -> range -> items  = $range;
        $data -> range -> max    = max( $range );

        // General Information

        $count = count( $range );

        $data -> count          = $count;
        $data -> itemsPerPage   = $this -> itemsPerPage;

        // Pages Information

        $data -> first   = 1;
        $data -> current = $this -> currentPage;
        $data -> last    = count( $this );

        if( ( $this -> currentPage - 1 ) > 0 ) {

            $data -> previous = ( $this -> currentPage - 1 );
        }

        if( ( $this -> currentPage + 1 ) <= $count ) {

            $data -> next = ( $this -> currentPage + 1 );
        }

        $data -> showFirst = FALSE;
        $data -> showLast  = FALSE;

        /**
         * @internal
         * First / Last Accessors
         *
         * Encapsulates the logic in the Paginator, instead of in Application's Controller
         */
        if( $data -> range -> min != $data -> first ) {

            $data -> showFirst = TRUE;
        }

        if( $data -> range -> max != $data -> last ) {

            $data -> showLast = TRUE;
        }

        return $data;
    }

    // Accessors

    /**
     * Get Pagination Adapter
     *
     * @return Next\Paginate\Adapter\Adapter
     *   Pagination Adapter Object
     */
    public function getAdapter() {
        return $this -> adapter;
    }

    /**
     * Get Scrolling Style
     *
     * @return Next\Paginate\Style\Style
     *   Pagination Style Algorithm Object
     */
    public function getStyle() {
        return $this -> style;
    }

    /**
     * Set Scrolling Style
     *
     * @param Next\Paginate\Style\Style $style
     *   Paginate Algorithm Style
     *
     * @return Next\Paginate\Paginator
     *   Paginator Object (Fluent Interface)
     */
    public function setStyle( Style $style ) {

        $this -> style =& $style;

        return $this;
    }

    /**
     * Get Current Page
     *
     * @return integer
     *   Current Page
     */
    public function getCurrentPage() {

        $total = count( $this );

        return ( ( $total > 0 && $this -> currentPage > $total ) ? $total : $this -> currentPage );
    }

    /**
     * Set Current Page
     *
     * @param integer $page
     *   Current Page
     *
     * @return Next\Paginate\Paginator
     *   Paginator Object (Fluent Interface)
     */
    public function setCurrentPage( $page ) {

        $page = (int) $page;

        // It must always be greater than zero

        $this -> currentPage = ( $page <= 0 ? 1 : $page );

        return $this;
    }

    /**
     * Get Items Per Page
     *
     * @return integer
     *   Number of Items to be displayed per page
     */
    public function getItemsPerPage() {
        return $this -> itemsPerPage;
    }

    /**
     * Set Items Per Page
     *
     * @param integer $amount
     *   Number of items per page
     *
     * @return Next\Paginate\Paginator
     *   Paginator Object (Fluent Interface)
     */
    public function setItemsPerPage( $amount ) {

        $this -> itemsPerPage = (integer) $amount;

        return $this;
    }

    // Countable Interface Method Implementation

    /**
     * Count elements of Paginator object
     *
     * In fact this acts as a wrapper to
     * Next\Paginate\Adapter\Adapter::count() so counting routine
     * is not triggered twice
     *
     * @return integer
     *   Number of paginated elements
     */
    public function count() {

        if( is_null( $this -> count ) ) {

            $this -> count = count( $this -> adapter );
        }

        return $this -> count;
    }

    // IteratorAggregate Interface Method Implementation

    /**
     * Get Iterator
     *
     * Get a foreach-compatible external Iterator or anything traversable
     * by a foreach loop
     *
     * This mean the Paginate Adapter is not REQUIRED to return a
     * Traversable Class
     *
     * @note This \@return DocType is only marked as Traversable due
     * IteratorAggregate Interface documentation compatibility
     *
     * @return Traversable
     *   Traversable Element
     */
    public function getIterator() {

        return $this -> adapter -> getItems(

            ( $this -> getInfo() -> range -> min - 1 ), $this -> itemsPerPage
        );
    }
}