<?php

namespace Next\File\Upload\Methods;

use Next\Validate\Validate;                          # Validate Interface
use Next\File\Upload\PostProcessor\PostProcessor;    # Post Processor Interface

interface Method {

    /**
     * Handles the uploading process
     */
    public function handle();

    /**
     * Add an Object to Validators Chain
     *
     * @param Next\Validate\Validate $validator
     */
    public function addValidator( Validate $validator );

    /**
     * Get external Validators
     */
    public function getValidators();

    /**
     * Add an Upload Post-Processor
     *
     * @param Next\File\Upload\PostProcessor $processor
     *  Upload Post-Processor
     */
    public function addPostProcessor( PostProcessor $processor );

    /**
     * Get upload Post-Processors
     */
    public function getPostProcessors();

    /**
     * Get files successfully uploaded
     */
    public function getSucceed();

    /**
     * Get files unsuccessfully uploaded
     */
    public function getFailed();
}