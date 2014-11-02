<?php

namespace Next\Validate\HTTP\Headers;

use Next\Components\Object;    # Object Class

/**
 * Raw Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Raw extends Object implements Headers {

    /**
     * Validates Generic Header Field
     *
     * @param string $data
     *  Data to validate
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate( $data ) {

        // Raw Header doesn't need to be validated

        return TRUE;
    }
}
