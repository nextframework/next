<?php

namespace Next\File\Upload\PostProcessor;

interface PostProcessor {

    /**
     * Executes post-processes routines over uploaded file
     *
     * @param  string $file
     *   Uploaded File
     */
    public function execute( $file );
}