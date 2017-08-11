<?php

/**
 * File Upload Post-Processor Interface | File\Upload\PostProcessor\PostProcessor.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\File\Upload\PostProcessor;

/**
 * Defines all methods that must be present in an File Upload Post-Processing Class
 *
 * @package    Next\File\Upload
 */
interface PostProcessor {

    /**
     * Executes post-processes routines over uploaded file
     *
     * @param string $file
     *   Uploaded File
     */
    public function execute( $file );
}