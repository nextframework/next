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
 * Routes Generator Output Writer Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2016 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Writer {

    /**
     * Saves found Routes to be used by Router Classes
     *
     * @param array $data
     *  Data to be written
     */
    public function save( array $data );

    /**
     * Resets the Writer to be used again
     */
    public function reset();
}
