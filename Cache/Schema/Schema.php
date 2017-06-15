<?php

/**
 * Caching Schemas Interface | Cache\Schema\Schema.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Cache\Schema;

/**
 * Defines the Caching Schema Type, with all methods that must be present
 * in an Caching Schema, be it through \Next\Cache\Schema\AbstractSchema
 * or the concrete implementations of it
 *
 * @package    Next\Cache\Schema
 */
interface Schema {

    /**
     * Caching Routine to be executed by \Next\Controller\Front
     */
    public function run();
}
