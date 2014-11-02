<?php

namespace Next\Validate;

/**
 * Validator Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Validate {

    /**
     * Validates given Character Set
     *
     * @param string $data
     *  Data to Validate
     */
    public function validate( $data );
}
