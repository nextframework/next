<?php

namespace Next\HTTP\Stream\Adapter;

use Next\HTTP\Stream\Context\Context;    # Stream Context Interface
use Next\Components\Object;              # Object Class

/**
 * HTTP Stream Adapter Abstract Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractAdapter extends Object implements Adapter {

    /**
     * Filename (or URL)
     *
     * @var string $filename
     */
    protected $filename;

    /**
     * Stream Resource
     *
     * @var mixed $stream
     */
    protected $stream;

    /**
     * Stream Context
     *
     * @var Next\HTTP\Stream\Context\Context $context
     */
    protected $context;

    // Interface Methods Implementation

    /**
     * Get Stream Filename (or URL)
     *
     * @return string
     *   Opened File or URL
     */
    public function getFilename() {
        return $this -> filename;
    }

    /**
     * Get Stream Resource
     *
     * @return mixed
     *   Stream Resource
     */
    public function getStream() {
        return $this -> stream;
    }

    /**
     * Set Adapter Context
     *
     * @param Next\HTTP\Stream\Context\Context $context
     *   Context Object
     *
     * @return Next\HTTP\Stream\Adapter\Adapter
     *   Stream Adapter Object (Fluent Interface)
     */
    public function setContext( Context $context ) {

        $this -> context =& $context;

        return $this;
    }

    /**
     * Get Adapter Context
     *
     * @return Next\HTTP\Stream\Context\Context
     *   Stream Context Object (Fluent Interface)
     */
    public function getContext() {
        return $this -> context;
    }
}
