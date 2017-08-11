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
 * Prototypable Objects allows resources to be prototyped to their
 * future instances, Prototypes in JavaScript
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