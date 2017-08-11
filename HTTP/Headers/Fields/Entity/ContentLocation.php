<?php

/**
 * HTTP Entity Header Field Class: Content-Location | HTTP\Headers\Fields\Entity\ContentLocation.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Fields\Entity;

use Next\HTTP\Headers\Fields\Entity;           # Entity Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;    # Header Field Abstract Class

/**
 * Entity 'Content-Location' Header Field Validator Class
 */
use Next\Validate\HTTP\Headers\Entity\ContentLocation as Validator;

/**
 * 'Content-Location' Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ContentLocation extends AbstractField implements Entity {

    // Abstract Methods Implementation

    /**
     * Get Header Field Validator
     *
     * @param mixed|string $value
     *  Header value to be validated
     *
     * @return \Next\Validate\Validator
     *  Associated Validator
     */
    protected function getValidator( $value ) {
        return new Validator( array( 'value' => $value ) );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'Content-Location' );
    }
}
