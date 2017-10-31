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
 * An Interface for all Routes Generators
 *
 * @package    Next\HTTP
 */
interface Generator {

    /**
     * Find Routes from Page Controllers' Action Methods DocBlocks
     */
    public function find();

    /**
     * Get elapsed time
     */
    public function getElapsedTime();
}
