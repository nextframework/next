<?php

/**
 * Parameterizable Component Interface | Components\Interfaces\Parameterizable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Interfaces;

/**
 * Parameterizable Objects make use of \Next\Components\Parameter
 * to provide a way to customize classes functionality with
 * Default, Common and Use Options
 *
 * @package    Next\Components\Interfaces
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