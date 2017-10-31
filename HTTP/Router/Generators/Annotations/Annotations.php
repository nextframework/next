<?php

/**
 * Routes Generator Annotations Interface | HTTP\Router\Generators\Annotations\Annotations.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Router\Generators\Annotations;

/**
 * An Interface for all Annotations Parser Generator
 *
 * @package    Next\HTTP
 */
interface Annotations {

    /**
     * Get Annotations Found
     */
    public function getAnnotations();
}
