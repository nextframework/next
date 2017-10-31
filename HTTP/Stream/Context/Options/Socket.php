<?php

/**
 * HTTP Stream Context Options Class: Socket | HTTP\Stream\Context\Options\Socket.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream\Context\Options;

/**
 * HTTP Stream Context Options Class for Stream Sockets (FileSystem) Contexts
 *
 * @package    Next\HTTP
 *
 * @uses       Next\HTTP\Stream\Context\Options\AbstractOptions
 *
 * @deprecated
 *
 * @FIXME
 */
class Socket extends AbstractOptions {

    // Interface Methods Implementation

    /**
     * Get Wrapper Name
     *
     * @return string
     *  Context Wrapper Name
     */
    public function getWrapperName() : string {
        return 'socket';
    }

    /**
     * Check if Context Option is acceptable by wrapper
     *
     * @param string $option
     *  Context Option to be checked
     *
     * @return boolean
     *  TRUE if Context Options is acceptable and FALSE otherwise
     */
    public function accept( $option ) : bool {
        return ( in_array( $option, [ 'bindto', 'backlog' ] ) );
    }
}
