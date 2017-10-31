<?php

/**
 * HTTP Stream Context Options Class: cURL | HTTP\Stream\Context\Options\Curl.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream\Context\Options;

/**
 * HTTP Stream Context Options Class for cURL Contexts
 *
 * @package    Next\HTTP
 *
 * @uses       Next\HTTP\Stream\Context\Options\AbstractOptions
 *
 * @deprecated
 *
 * @FIXME
 */
class Curl extends AbstractOptions {

    // Interface Methods Implementation

    /**
     * Get Wrapper Name
     *
     * @return string
     *  Context Wrapper Name
     */
    public function getWrapperName() : string {
        return 'curl';
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

        $valid = [
            'method', 'header', 'user_agent', 'content', 'proxy',
            'max_redirects', 'curl_verify_ssl_host', 'curl_verify_ssl_peer'
        ];

        return ( in_array( $option, $valid ) );
    }
}
