<?php

namespace Next\Validate;

/**
 * Validatable Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Validatable {

    /**
     * Validates given data
     */
    public function validate();
}
