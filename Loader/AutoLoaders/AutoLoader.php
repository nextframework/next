<?php

/**
 * AutoLoaders Interface | Loader\AutoLoader\AutoLoadable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Loader\AutoLoaders;

/**
 * An Interface for all AutoLoader Strategies
 *
 * @package    Next\Loader
 */
interface AutoLoader {

    /**
     * AutoLoading Function
     */
    public function call() : callable;
}
