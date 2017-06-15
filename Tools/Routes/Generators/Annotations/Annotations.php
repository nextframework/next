<?php

/**
 * Routes Generator Annotations Interface | Tools\Routes\Generators\Annotations\Annotations.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
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
