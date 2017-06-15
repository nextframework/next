<?php

/**
 * Class Mapper Interface | HTTP\Stream\Reader\Reader.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Tools\ClassMapper;

/**
 * Mapper Output Format Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Mapper {

    /**
     * Map Builder
     *
     * @param array $map
     *  Mapped Array
     */
    public function build( array $map );
}
