<?php

namespace Next\HTTP\Stream\Context\Options;

/**
 * Stream Context FTP Options Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class FTP extends AbstractOptions {

    // Interface Methods Implementation

    /**
     * Get Wrapper Name
     *
     * @return string
     *   Context Wrapper Name
     */
    public function getWrapperName() {
        return 'ftp';
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
        return ( in_array( $option, array( 'overwrite', 'resume_pos', 'proxy' ) ) );
    }
}
