<?php

/**
 * Caching Schemas Interface | Cache\Schema\Schema.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Cache\Schemas;

/**
 * Defines the Caching Schema Type, with all methods that must be present
 * in an Caching Schema, be it through \Next\Cache\Schemas\AbstractSchema
 * or the concrete implementations of it
 *
 * @package    Next\Cache
 */
interface Schema {

    /**
     * Caching Routine to be executed by \Next\Controller\Front
     */
    public function run();
}
