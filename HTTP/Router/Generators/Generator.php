<?php

/**
 * Routes Generators Data Generators Interface | HTTP\Router\Generators\Generator.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Router\Generators;

/**
 * Routes Generator Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2016 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Generator {

    /**
     * Find Routes from Controllers' Methods DocBlocks
     */
    public function find();

    /**
     * Get elapsed time
     */
    public function getElapsedTime();
}
