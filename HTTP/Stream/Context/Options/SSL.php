<?php

/**
 * HTTP Stream Context Options Class: SSL | HTTP\Stream\Context\Options\Curl.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream\Context\Options;

/**
 * Stream Context SSL Options Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class SSL extends AbstractOptions {

    // Interface Methods Implementation

    /**
     * Get Wrapper Name
     *
     * @return string
     *  Context Wrapper Name
     */
    public function getWrapperName() {
        return 'ssl';
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
    public function accept( $option ) {

        $valid = [
            'verify_peer', 'allow_self_signed', 'cafile', 'capath', 'local_cert',
            'passphrase', 'CN_match', 'verify_depth', 'ciphers', 'capture_peer_cert',
            'capture_peer_cert_chain', 'SNI_enabled', 'SNI_server_name'
        ];

        return ( in_array( $option, $valid ) );
    }
}
