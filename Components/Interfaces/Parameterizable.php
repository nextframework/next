<?php

namespace Next\Components\Interfaces;

/**
 * Parameterizable Interface
 *
 * Parameterizable Objects are assumed to use Next\Components\Parameter
 * to provide a way to customize classes functionality theough
 * Default, Common and Use Options
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Parameterizable {

    /**
     * Set Child Classes Options
     */
    public function setOptions();

    /**
     * Get Class Options
     */
    public function getOptions();
}