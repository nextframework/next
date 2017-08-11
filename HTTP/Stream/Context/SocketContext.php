<?php

/**
 * HTTP Stream Socket Context Class | HTTP\Stream\Context\SocketContext.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream\Context;

/**
 * Stream Context Socket Context Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class SocketContext extends AbstractContext {

    // Abstract Method Implementation

    /**
     * Create a Stream Context
     *
     * @return resource
     *  Stream Context Resource
     */
    protected function createContext() {
        return stream_context_create( $this -> options );
    }
}
