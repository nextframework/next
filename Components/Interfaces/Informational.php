<?php

namespace Next\Components\Interfaces;

/**
 * Informational Interface
 *
 * Informational Objects are assumed to provide information messages about
 * their processing, success or error
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2014 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Informational {

    /**
     * Get success message
     */
    public function getSuccessMessage();

    /**
     * Get error message
     */
    public function getErrorMessage();

    /**
     * Get informational message
     */
    public function getInformationalMessage();
}