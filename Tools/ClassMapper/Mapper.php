<?php

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
