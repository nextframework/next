<?php

namespace Next\Cache\Schema;

use Next\Application\Application;    # Application Interface
use Next\Components\Object;          # Object Class

/**
 * Caching Schema Abstract Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2017 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractSchema extends Object implements Schema {

    /**
     * Application Object
     *
     * @var Next\Application\Application $application
     */
    protected $application;

    /**
     * Constructor Overwriting
     * Sets up a type-hinted Application Object for all Caching Schema
     *
     * @param Next\Application\Application $application
     *  Application Object
     *
     * @param mixed|Next\Components\Object|Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for Caching Schema
     */
    public function __construct( Application $application, $options = NULL ) {

        parent::__construct( $options );

        $this -> application = $application;
    }
}