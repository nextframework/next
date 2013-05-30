<?php

namespace Next\Components\Decorators;

use Next\Components\Object;     # Object Class

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
     *  @param string $message
     *    Resource to decorate
     */
    public function __construct( $resource ) {

        parent::__construct();

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