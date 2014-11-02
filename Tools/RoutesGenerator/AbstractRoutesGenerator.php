<?php

namespace Next\Tools\RoutesGenerator;

use Next\Components\Object;                    # Object Class
use Next\Application\Chain as Applications;    # Application Chain Class

/**
 * Routes Generator Tool: Generators Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractRoutesGenerator extends Object implements RoutesGenerator {

    /**
     * Applications Chain
     *
     * @var Next\Application\Chain $applications
     */
    protected $applications;

    /**
     * Routes Results
     *
     * @var array $results
     */
    protected $results = array();

    /**
     * Time Elapsed
     *
     * @var float $startTime
     */
    protected $startTime;

    /**
     * Routes Generator Constructor
     *
     * @param Next\Application\Chain $applications
     *  Applications Chain to iterate through
     */
    public function __construct( Applications $applications ) {

        // Setting Up Resources

            // Start Time (for final message)

        $this -> startTime = microtime( TRUE );

            // Applications' Chain

        $this -> applications =& $applications;

            // Additional Initialization

        $this -> init();
    }

    /**
     * Additional Initialization. Must be overwritten
     */
    protected function init() {}
}