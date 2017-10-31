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

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Interfaces\Configurable;    # Configurable Interface
use Next\Validation\Verifiable;                 # Verifiable Interface
use Next\Components\Object;                     # Object Class
use Next\Pagination\Styles\Sliding;             # Default Pagination Scrolling Style

/**
 * The Paginator provides a simple data-structure with all informations
 * needed to render a pagination accordingly to chosen Style
 *
 * @package    Next\Pagination
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Interfaces\Configurable
 *             Next\Validation\Verifiable
 *             Next\Components\Object
 *             Next\Pagination\Styles\Sliding
 *             Countable
 *             stdClass
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

        'visiblePages' => [ 'required' => FALSE, 'default' => 5 ],
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
    protected function init() : void {

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
    public function getInfo() : \stdClass {

        $data = new \stdClass;

        // Range Information

        $data -> range = new \stdClass;

        $interval = $this -> options -> style -> getInterval();

        $data -> range -> min    = min( $interval );
        $data -> range -> items  = $interval;
        $data -> range -> max    = max( $interval );
        $data -> range -> count  = count( $this -> options -> style );

        // Pages Information

        $data -> visiblePages   = $this -> options -> visiblePages;

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
     *  Thrown if initial value of Parameter Option 'visiblePages' is not an
     *  integer or it's less than 1
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if initial value of Parameter Option 'currentPage' is not an
     *  integer or it's less than 1
     */
    public function verify() : void {

        if( ! is_int( $this -> options -> visiblePages ) ||
                $this -> options -> visiblePages < 1 ) {

            throw new InvalidArgumentException(

                'Parameter Option <strong>visiblePages</strong>
                must be an integer greater or equal 1'
            );
        }

        if( ! is_int( $this -> options -> currentPage ) ||
                $this -> options -> currentPage < 1 ) {

            throw new InvalidArgumentException(
                'Parameter Option <strong>currentPage</strong> must be
                an integer greater or equal 1'
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
    public function getCurrentPage() : int {

        return ( ( $this -> count > 0 &&
                    $this -> options -> currentPage > $this -> count ) ?
                        $this -> count : $this -> options -> currentPage );
    }

    /**
     * Get Items Per Page
     *
     * @return integer
     *  Number of Items to be displayed per page
     */
    public function getItemsPerPage() : int {

        return ( $this -> options -> visiblePages > $this -> count ?
                    $this -> options -> visiblePages : $this -> count );
    }

    // Countable Interface Method Implementation

    /**
     * Count elements of Paginator object
     *
     * @internal
     *
     * This is an interface alias of Next\Pagination\Adapter\Adapter::count()`
     * so counting routine is not triggered twice
     *
     * @return integer
     *  Number of paginated elements
     */
    public function count() : int {
        return $this -> count;
    }
}