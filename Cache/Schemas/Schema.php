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
 * An Interface for all Caching Schema Strategies
 *
 * @package    Next\Cache
 */
interface Schema {

    /**
     * Caching Routine to be executed by Next\Controller\Front`
     */
    public function run() : void;
}
