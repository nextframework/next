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
     * @param string $message
     *   Resource to decorate
     *
     * @param mixed|Next\Components\Object|Next\Components\Parameter|stdClass|array|optional $options
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