<?php

/**
 * Routes Generator Annotations Interface | Tools\Routes\Generators\Annotations\Annotations.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Tools\Routes\Generators\Annotations;

/**
 * Defines all methods that must present in a Routes Generator Analyzer
 *
 * @package    Next\Tools\Routes\Generators
 */
interface Annotations {

    /**
     * Get Annotations Found
     */
    public function getAnnotations();
}
