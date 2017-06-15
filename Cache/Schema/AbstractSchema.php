<?php

/**
 * Caching Schema Abstract Class | Cache\Schema\AbstractSchema.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Cache\Schema;

use Next\Application\Application;    # Application Interface
use Next\Components\Object;          # Object Class

/**
 * Defines the base structure for a Caching Schema created with Next Framework
 *
 * @package    Next\Caching\Schema
 */
abstract class AbstractSchema extends Object implements Schema {

    /**
     * Application Object
     *
     * @var \Next\Application\Application $application
     */
    protected $application;

    /**
     * Constructor Overwriting
     * Sets up a type-hinted Application Object for all Caching Schema
     *
     * @param \Next\Application\Application $application
     *  Application Object
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for Caching Schema
     */
    public function __construct( Application $application, $options = NULL ) {

        parent::__construct( $options );

        $this -> application = $application;
    }
}