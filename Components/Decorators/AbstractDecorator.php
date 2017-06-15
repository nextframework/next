<?php

/**
 * Decorators Component Abstract Class | Components\Decorators\AbstractDecorator.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Decorators;

use Next\Components\Object;     # Object Class

/**
 * Defines the base structure for a Decorator created with Next Framework
 *
 * @package    Next\Application
 */
abstract class AbstractDecorator extends Object implements Decorator {

    /**
     * Decoratable Resource
     *
     * @var mixed $resource
     */
    protected $resource;

    /**
     * Decorator Constructor
     *
     * @param string $resource
     *   Resource to decorate
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for each Decorator
     */
    public function __construct( $resource, $options = NULL ) {

        parent::__construct( $options );

        $this -> resource =& $resource;
    }

    // Decorator Interface Method Implementation

    /**
     *  Get decorated resource
     *
     *  @return mixed
     *    Decorated Resource
     */
    public function getResource() {
        return $this -> resource;
    }
}