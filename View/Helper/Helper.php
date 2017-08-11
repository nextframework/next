<?php

/**
 * View Helper Interface | View\Helper\Helper.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
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