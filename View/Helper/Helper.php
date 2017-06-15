<?php

/**
 * View Helper Interface | View\Helper\Helper.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\View\Helper;

/**
 * Defines all methods that must present in a View Engine Helper
 *
 * @package    Next\View\Helper
 */
interface Helper {

    /**
     * Get the Helper name to be registered by View Engine
     */
    public function getHelperName();
}