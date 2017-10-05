<?php

/**
 * ClassMapper Output Writer Interface | FileSystem\Prototypes\ClassMapper\Writer\Writer.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\FileSystem\Prototypes\ClassMapper\Writer;

/**
 * Mapper Output Format Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2017 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Writer {

    /**
     * Classmapper Output Builder
     *
     * @param array $map
     *  Classmap array
     */
    public function build( array $map );
}
