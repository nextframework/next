<?php

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
     *   Context Wrapper Name
     */
    public function getWrapperName() {
        return 'ssl';
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

        $valid = array(

            'verify_peer', 'allow_self_signed', 'cafile', 'capath', 'local_cert',
            'passphrase', 'CN_match', 'verify_depth', 'ciphers', 'capture_peer_cert',
            'capture_peer_cert_chain', 'SNI_enabled', 'SNI_server_name'
        );

        return ( in_array( $option, $valid ) );
    }
}
