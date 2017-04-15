<?php

namespace Next\Components;

use Next\Components\Object;    # Object Class

class Mimicker extends Object {

    /**
     * Mimicker object
     *
     * @var object $mimicker
     */
    protected $mimicker;

    /**
     * Constructor Overwriting
     * Instantiates the real class mimicking an instance of Next\Components\Object
     *
     * @param object $mimicker
     *  Object trying to mimic an instance of Next\ComponentsObject
     *
     * @param mixed|Next\Components\Object|Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for Object Mimicker Class
     *
     * @throws Next\Components\ContextException
     *  Thrown if resource being mimicked is not an object
     */
    public function __construct( $mimicker, $options = NULL ) {

        parent::__construct( $options );

        // Only objects can (or need to) be mimicked

        if( ! is_object( $mimicker ) ) {
            throw ContextException::notMimicable();
        }

        $this -> mimicker = new $mimicker;
    }

    // Accessors

    /**
     * Get mimicker Object
     *
     * @return object
     *  Mimicked Object
     */
    public function getMimicker() {
        return $this -> mimicker;
    }
}