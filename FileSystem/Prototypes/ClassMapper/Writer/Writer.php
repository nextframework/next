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
 * An Interface for all ClassMapper Output Writing Strategies
 *
 * @package    Next\FileSystem
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
