<?php

/**
 * Paginator Class | Pagination\Paginator.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Pagination;

use Next\Components\Interfaces\Configurable;    # Configurable Interface
use Next\Components\Interfaces\Verifiable;      # Verifiable Interface
use Next\Components\Object;                     # Object Class
use Next\Pagination\Styles\Sliding;             # Default Pagination Scrolling Style

/**
 * Paginator Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Paginator extends Object implements Verifiable, \Countable {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        'adapter' => [ 'type' => 'Next\Pagination\Adapter\Adapter', 'required' => TRUE ],
        'style'   => [ 'type' => 'Next\Pagination\Styles\Style',    'required' => TRUE ],

        'itemsPerPage' => [ 'required' => FALSE, 'default' => 5 ],
        'currentPage'  => [ 'required' => FALSE, 'default' => 1 ]
    ];

    /**
     * Total of Records
     *
     * @var integer $count
     */
    private $count;

    /**
     * Additional Initialization.
     * Injects itself in Pagination Style Class and executes
     * post-initialization routines if needed
     *
     * @see \Next\Components\Interfaces\Configurable
     */
    protected function init() {

        $this -> count = count( $this -> options -> adapter );

        $this -> options -> style -> setPaginator( $this );

        // Executing Pagination Style post-configuration

        if( $this -> options -> style instanceof Configurable ) {
            $this -> options -> style -> configure();
        }
    }

    // Accessors

    /**
     * Get Pagination Informations
     *
     * @return stdClass Object
     *  Pagination Informations
     */
    public function getInfo() {

        $data = new \stdClass;

        // Range Information

        $data -> range = new \stdClass;

        $interval = $this -> options -> style -> getInterval();

        $data -> range -> min    = min( $interval );
        $data -> range -> items  = $interval;
        $data -> range -> max    = max( $interval );
        $data -> range -> count  = count( $this -> options -> style );

        // Pages Information

        $data -> itemsPerPage   = $this -> options -> itemsPerPage;

        $data -> current = ( $this -> options -> currentPage < $this -> count ?
                                $this -> options -> currentPage : $this -> count );

        $data -> first   = 1;
        $data -> last    = $this -> count;

        if( $this -> options -> currentPage != $data -> range -> min ) {

            $data -> previous = ( $this -> options -> currentPage - 1 );
        }

        if( $this -> options -> currentPage != $data -> range -> max ) {

            $data -> next = ( $this -> options -> currentPage + 1 );
        }

        $data -> showFirst = FALSE;
        $data -> showLast  = FALSE;

        /**
         * @internal
         *
         * First / Last Accessors
         *
         * Encapsulates the logic in Paginator, instead of in Application's Controller
         */
        if( $data -> range -> min != $data -> first ) {

            $data -> showFirst = TRUE;
        }

        if( $data -> range -> max != $data -> last ) {

            $data -> showLast = TRUE;
        }

        return $data;
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if initial value of Parameter Option 'itemsPerPage' is not an
     *  integer or it's less than 1
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if initial value of Parameter Option 'currentPage' is not an
     *  integer or it's less than 1
     */
    public function verify() {

        if( ! is_int( $this -> options -> itemsPerPage ) || $this -> options -> itemsPerPage < 1 ) {

            throw new InvalidArgumentException(
                'Parameter Option <strong>itemsPerPage</strong> must be an integer greater or equal 1'
            );
        }

        if( ! is_int( $this -> options -> currentPage ) || $this -> options -> currentPage < 1 ) {

            throw new InvalidArgumentException(
                'Parameter Option <strong>currentPage</strong> must be an integer greater or equal 1'
            );
        }
    }

    // Accessory Methods

    /**
     * Get Current Page
     *
     * @return integer
     *  Current Page
     */
    public function getCurrentPage() {

        return ( ( $this -> count > 0 && $this -> options -> currentPage > $this -> count ) ?
                    $this -> count : $this -> options -> currentPage );
    }

    /**
     * Get Items Per Page
     *
     * @return integer
     *  Number of Items to be displayed per page
     */
    public function getItemsPerPage() {

        return ( $this -> options -> itemsPerPage > $this -> count ?
                    $this -> options -> itemsPerPage : $this -> count );
    }

    // Countable Interface Method Implementation

    /**
     * Count elements of Paginator object
     *
     * In fact this acts as a wrapper to
     * \Next\Pagination\Adapter\Adapter::count() so counting routine
     * is not triggered twice
     *
     * @return integer
     *  Number of paginated elements
     */
    public function count() {
        return $this -> count;
    }
}