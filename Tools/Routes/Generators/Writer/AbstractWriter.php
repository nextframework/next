<?php

namespace Next\Tools\Routes\Generators\Writer;

use Next\Components\Object;       # Object Class
use Next\Components\Parameter;    # Parameter Class

/**
 * Routes Generator Tool: Output Writer Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2016 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractWriter extends Object implements Writer {

    /**
     * Routes Generators Default Options
     *
     * @var array $defaultOptions
     */
    private $defaultOptions = array();

    /**
     * Routes Generators Options
     *
     * @var Next\Components\Parameter $options
     */
    protected $options;

    /**
     * Routes Generator Output Writer Constructor
     *
     * @param mixed|Next\Components\Object|Next\Components\Parameter|stdClass|array|optional $options
     *  Configuration options for Routes Generators
     *
     * @see Next\Components\Parameter
     */
    public function __construct( $options = NULL ) {

        // Parameter Object (options)

        $this -> options = new Parameter( $this -> defaultOptions, $options );

        // Integrity Check

        $this -> checkRequirements();

        // Additional Initialization

        $this -> init();
    }

    /**
     * Additional Initialization. Must be overwritten
     */
    protected function init() {}

    /**
     * Integrity Check. Must be overwritten
     */
    protected function checkRequirements() {}
}