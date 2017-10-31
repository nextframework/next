<?php

/**
 * Hashable Interface | Components\Interfaces\Hashable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Interfaces;

/**
 * Hashable Objects provides a string representation of the Object allowing
 * them to be used as array indexes
 *
 * @package    Next\Components\Interfaces
 */
interface Hashable {

    /**
     * Get Object hash
     */
    public function hash() : string;
}