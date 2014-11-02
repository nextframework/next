<?php

namespace Next\Cache;

use Next\Cache\BackEnd\Backend;    # Backend Interface

/**
 * Cacheable Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Cacheable {

    /**
     * Set Cache Backend
     *
     * @param Next\Cache\Backend\Backend $backend
     *  Cache Backend
     */
    public function setCacheBackend( Backend $backend );

    /**
     * Get Cache Backend
     */
    public function getBackend();
}
