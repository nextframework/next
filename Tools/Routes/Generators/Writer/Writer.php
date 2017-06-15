<?php

/**
 * Routes Generator Output Writers Interface | Tools\Routes\Generators\Writer\Writer.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Tools\Routes\Generators\Writer;

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
