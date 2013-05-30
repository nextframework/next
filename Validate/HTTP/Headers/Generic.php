<?php

namespace Next\Validate\HTTP\Headers;

use Next\Components\Object;    # Object Class

/**
 * Generic Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Generic extends Object implements Headers {

    /**
     * Validates Generic Header Field
     *
     * @param string $data
     *   Data to validate
     *
     * @return boolean
     *   TRUE if valid and FALSE otherwise
     */
    public function validate( $data ) {

        /**
         * @internal
         * Generic Headers accept everything without requiring any kind
         * of validation routine BUT, in order to make sure the minimal
         * requirements are being followed, let's look for a semi-colon
         * and the lack o 'Generic' keyword
         */
        return ( ( strpos( $data, ':' ) === FALSE || stripos( $data, 'Generic' ) !== FALSE ) ? FALSE : TRUE );
    }
}
