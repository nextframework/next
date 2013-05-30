<?php

namespace Next\Components\Decorators;

interface Decorator {

    /**
     *  Decorate Resource
     */
    public function decorate();

    /**
     *  Get decorated resource
     */
    public function getResource();
}