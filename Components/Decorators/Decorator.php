<?php

/**
 * Decorator Interface | Components\Decorator\Decorator.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Decorators;

/**
 * Defines the Decorator Type, with all methods that must
 * be present in an Decorator
 *
 * @package    Next\Components\Decorators
 */
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