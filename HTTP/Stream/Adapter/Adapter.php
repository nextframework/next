<?php

/**
 * HTTP Stream Adapter Interface | HTTP\Stream\Adapter\Adapter.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream\Adapter;

use Next\HTTP\Stream\Context\Context;    # Stream Context Interface

/**
 * An Interface for all HTTP Stream Adapters
 *
 * @package    Next\HTTP
 *
 * @uses       Next\HTTP\Stream\Context\Context
 *             SeekableIterator
 */
interface Adapter extends \SeekableIterator {

    /**
     * Open a File (or URL)
     */
    public function open();

    /**
     * Close opened Stream
     */
    public function close();

    /**
     * Test if Stream has achieved the End of File
     */
    public function eof();

    /**
     * Tell the current position of Stream Pointer
     */
    public function tell();

    /**
     * Get the size of Stream
     */
    public function size();

    /**
     * Check if Stream was opened, by testing its Resource
     */
    public function isOpened();

    /**
     * Get Stream Meta Data
     */
    public function getMetaData();

    /**
     * Get Stream Filename (or URL)
     */
    public function getFilename();

    /**
     * Get Stream Resource
     */
    public function getStream();

    /**
     * Set Adapter Context
     *
     * @param \Next\HTTP\Stream\Context\Context $context
     *  Stream Context
     */
    public function setContext( Context $context );

    /**
     * Get Adapter Context
     */
    public function getContext();
}
