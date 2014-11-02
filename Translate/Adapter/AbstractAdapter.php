<?php

namespace Next\Translate\Adapter;

use Next\Cache\Backend\Backend;          # Cache Backend Interface
use Next\HTTP\Stream\Adapter\Adapter;    # HTTP Stream Adapter Interface
use Next\Components\Object;              # Object Class
use Next\HTTP\Stream\Reader;             # HTTP Stream Reader Class

/**
 * GetText Adapter Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractAdapter extends Object {

    /**
     * Stream Reader Object
     *
     * @var Next\HTTP\Stream\Reader $reader
     */
    protected $reader;

    /**
     * Cache Backend Object
     *
     * @var Next\Cache\Backend\Backend $cacheBackend
     */
    protected $cacheBackend;

    /**
     * GetText Adapter Constructor
     *
     * @param Next\HTTP\Stream\Adapter\Adapter|optional $adapter
     *  HTTP Stream Adapter used to read data
     */
    public function __construct( Adapter $adapter = NULL ) {

        $this -> reader = new Reader( $adapter );
    }

    // Cacheable Interface Methods

    /**
     * Set Cache Backend
     *
     * @param Next\Cache\Backend\Backend $backend
     *  Cache Backend
     *
     * @return Next\Translate\Adapter\Adapter
     *  Translate Adapter Object (Fluent Interface)
     */
    public function setCacheBackend( Backend $backend ) {

        $this -> cacheBackend = $backend;

        return $this;
    }

    /**
     * Get Cache Backend
     *
     * @return Next\Cache\Backend\Backend
     *  Cache Backend Object
     */
    public function getBackend() {
        return $this -> cacheBackend;
    }
}