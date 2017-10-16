<?php

/**
 * Routes Generator Abstract Generator Class | Controller\Router\Generators\AbstractGenerator.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller\Router\Generators;

use Next\Components\Object;                    # Object Class
use Next\Application\Chain as Applications;    # Application Chain Class

/**
 * Routes Generator Tool: Generators Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2016 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractGenerator extends Object implements Generator {

    /**
     * Applications Chain
     *
     * @var \Next\Application\Chain $applications
     */
    protected $applications;

    /**
     * Routes Results
     *
     * @var array $results
     */
    protected $results = [];

    /**
     * Time Elapsed
     *
     * @var float $startTime
     */
    protected $startTime;

    /**
     * Routes Generator Constructor
     *
     * @param \Next\Application\Chain $applications
     *  Applications Chain to iterate through
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Configuration options for Routes Generators
     *
     * @see \Next\Components\Parameter
     */
    public function __construct( Applications $applications, $options = NULL ) {

        // Start Time (for final message)

        $this -> startTime = microtime( TRUE );

        // Applications' Chain

        $this -> applications = $applications;

        parent::__construct( $options );
    }

    // Accessors

    /**
     * Get elapsed time
     *
     * @return double
     *  Elapsed time since the object of the chosen Generator was created
     */
    public function getElapsedTime() {
        return ( microtime( TRUE ) - $this -> startTime );
    }
}