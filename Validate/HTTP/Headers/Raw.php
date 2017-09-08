<?php

/**
 * HTTP Raw Header Field Validator Class | Validate\HTTP\Headers\Raw.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
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
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        // Raw Header doesn't need to be validated

        return TRUE;
    }

    // Parameterizable Interface Method Overriding

    /**
     * Set Class Options.
     * Defines which Parameter Options are known by the Validator Class
     *
     * @return array
     *  An array with Custom Options overriding or complementing Object defaults
     */
    public function setOptions() {
        return [ 'value' => [ 'required' => TRUE ] ];
    }
}
