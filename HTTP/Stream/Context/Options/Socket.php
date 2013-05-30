<?php

namespace Next\HTTP\Stream\Context\Options;

/**
 * Stream Context Socket Options Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Socket extends AbstractOptions {

    // Interface Methods Implementation

    /**
     * Get Wrapper Name
     *
     * @return string
     *   Context Wrapper Name
     */
    public function getWrapperName() {
        return 'socket';
    }

    /**
     * Check if Context Option is acceptable by wrapper
     *
     * @param string $option
     *   Context Option to be checked
     *
     * @return boolean
     *   TRUE if Context Options is acceptable and FALSE otherwise
     */
    public function accept( $option ) {
        return ( in_array( $option, array( 'bindto', 'backlog' ) ) );
    }
}
