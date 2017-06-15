<?php

namespace Next\File\Upload\Methods;

use Next\Validate\Validator;                         # Validator Interface
use Next\File\Upload\PostProcessor\PostProcessor;    # Post Processor Interface

interface Method {

    /**
     * Handles the uploading process
     */
    public function handle();

    /**
     * Add an external Validator to the Chain
     *
     * @param Next\Validate\Validator $validator
     */
    public function addValidator( Validator $validator );

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