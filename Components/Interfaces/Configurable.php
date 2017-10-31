<?php

/**
 * Configurable Interface | Components\Interfaces\Configurable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Interfaces;

/**
 * Configurable Objects are assumed to provide and/or require
 * post-initialization  — i.e. after Next\Components\Object::init()`
 *
 * @package    Next\Components\Interfaces
 */
interface Configurable {

    /**
     * Post-initialization Configuration
     */
    public function configure();
}