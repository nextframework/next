<?php

/**
 * File Upload Post-Processor Interface | File\Upload\PostProcessor\PostProcessor.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
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