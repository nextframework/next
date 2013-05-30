<?php

namespace Next\HTTP\Headers\Fields\Entity;

use Next\HTTP\Headers\Fields\Entity;        # Entity Headers Interface
use Next\HTTP\Headers\Fields\AbstractField; # Header Field Abstract Class

/**
 * Content-Encoding Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentEncoding extends AbstractField implements Entity {

    // Abstract Methods Implementation

    /**
     * Get Header Field Validator
     *
     * @return Next\Validate\Validate
     *   Associated Validator
     */
    protected function getValidator() {
        return new \Next\Validate\HTTP\Headers\Entity\ContentEncoding;
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *   Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'Content-Encoding', 'acceptMultiples' => TRUE );
    }
}
