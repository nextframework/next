<?php

/**
 * Class Mapper Interface | HTTP\Stream\Reader\Reader.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
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
