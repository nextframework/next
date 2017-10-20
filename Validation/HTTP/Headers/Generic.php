<?php

/**
 * HTTP Generic Header Field Validator Class | Validate\HTTP\Headers\Generic.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation\HTTP\Headers;

use Next\Components\Object;    # Object Class

/**
 * Generic Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Generic extends Object implements Header {

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
         * Generic Headers accept everything without requiring any kind
         * of validation routine BUT, in order to make sure the minimal
         * requirements are being followed, let's look for a semi-colon
         * and if the value is not the 'Generic' keyword
         */
        return ( ( strpos( $this -> options -> value, ':' ) === FALSE ||
                   stripos( $this -> options -> value, 'Generic' ) !== FALSE ) ? FALSE : TRUE );
    }
}