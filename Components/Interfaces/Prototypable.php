<?php

/**
 * Prototypable Components Interface | Components\Interface\Prototypable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\Components\Interfaces;

/**
 * Prototypable Objects prototypes new functionalities to a Prototypical Object,
 * effectively allowing them to be used later — considering the vertically
 * crescent Request Flow, of course
 *
 * @package    Next\Components\Interfaces
 */
interface Prototypable {

    /**
     * Prototypes a custom, and maybe complex, routine to one of
     * the available types by proxying, treating and handling
     * the mixed arguments received
     */
    public function prototype();
}