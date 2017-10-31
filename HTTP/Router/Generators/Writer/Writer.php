<?php

/**
 * Routes Generator Output Writers Interface | HTTP\Router\Generators\Writer\Writer.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Router\Generators\Writer;

/**
 * An Interface for all Routes Generator Output Writing Strategies
 *
 * @package    Next\HTPP
 */
interface Writer {

    /**
     * Saves found Routes to be used by Router Classes
     *
     * @param array $data
     *  Data to be written
     */
    public function save( array $data ) : int;

    /**
     * Resets the Writer to be used again
     */
    public function reset() : void;
}
