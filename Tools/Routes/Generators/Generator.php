<?php

namespace Next\Tools\Routes\Generators;

use Next\Components\Interfaces\Parameterizable;    # Parameterizable Interface

/**
 * Routes Generator Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2016 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Generator extends Parameterizable {

    /**
     * Find Routes from Controllers Methods DocBlocks
     */
    public function find();

    /**
     * Get elapsed time
     */
    public function getElapsedTime();
}
