<?php

namespace Next\Tools\RoutesGenerator;

/**
 * Routes Generator Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface RoutesGenerator {

    /**
     * Find Routes from Controllers Methods DocBlocks
     */
    public function find();

    /**
     * Save them, to be used by Router Classes
     */
    public function save();

    /**
     * Reset the Routes Storage, by formatting or cleaning it
     */
    public function reset();
}
