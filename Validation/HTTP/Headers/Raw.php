<?php

/**
 * HTTP Raw Header Field Validator Class | Validate\HTTP\Headers\Raw.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers;

use Next\Components\Object;    # Object Class

/**
 * Raw Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Raw extends Object implements Header {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'value' => [ 'required' => TRUE ]
    ];

    /**
     * Validates Generic Header Field
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     */
    public function validate() {

        /**
         * @internal
         *
         * Raw Headers accept anything, but we'll enforce it's at
         * least not NULL
         */
        return ( $this -> options -> value !== NULL );
    }
}
