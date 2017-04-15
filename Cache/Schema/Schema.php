<?php

namespace Next\Cache\Schema;

/**
 * Caching Schema Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2017 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Schema {

    /**
     * Caching Routine to be executed by Next\Controller\Front
     */
    public function run();
}
