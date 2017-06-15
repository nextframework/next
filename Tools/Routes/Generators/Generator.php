<?php

/**
 * Routes Generators Data Generators Interface | Tools\Routes\Generators\Generator.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
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
