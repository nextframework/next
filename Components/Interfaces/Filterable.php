<?php

/**
 * Filterable Interface | Components\Interfaces\Filterable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Interfaces;

/**
 * Filterable Objects filters out unwanted values, pretty much like
 * native FilterIterator class only that being an Interface does
 * imply inheritance, preventing Filterable Objects to be children
 * of \Next\Components\Object
 *
 * @package    Next\Components\Interfaces
 */
interface Filterable {

    /**
     * Filters the Object
     */
    public function filter();
}
