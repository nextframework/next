<?php

/**
 * HTTP Stream Abstract Adapter Class | HTTP\Stream\Adapter\AbstractAdapter.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream\Adapter;

use Next\HTTP\Stream\Context\Context;    # Stream Context Interface
use Next\Components\Object;              # Object Class

/**
 * Base structure for all HTTP Stream Adapters
 *
 * @package    Next\HTTP
 *
 * @uses       Next\HTTP\Stream\Context\Context
 *             Next\Components\Object
 *             Next\HTTP\Stream\Adapter\Adapter
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
     * @var \Next\HTTP\Stream\Context\Context $context
     */
    protected $context;

    // Interface Methods Implementation

    /**
     * Get Stream Filename (or URL)
     *
     * @return string
     *  Opened File or URL
     */
    public function getFilename() :? string {
        return $this -> filename;
    }

    /**
     * Get Stream Resource
     *
     * @return mixed|resource
     *  Stream Resource
     */
    public function getStream() {
        return $this -> stream;
    }

    /**
     * Set Adapter Context
     *
     * @param \Next\HTTP\Stream\Context\Context $context
     *  Context Object
     *
     * @return \Next\HTTP\Stream\Adapter\Adapter
     *  Stream Adapter Object (Fluent Interface)
     */
    public function setContext( Context $context ) :? Adapter {

        $this -> context = $context;

        return $this;
    }

    /**
     * Get Adapter Context
     *
     * @return \Next\HTTP\Stream\Context\Context
     *  Stream Context Object (Fluent Interface)
     */
    public function getContext() : Context {
        return $this -> context;
    }
}
