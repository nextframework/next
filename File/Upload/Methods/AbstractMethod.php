<?php

/**
 * HTTP File Upload Abstract Method Class | HTTP\File\Upload\Methods\AbstractMethod.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\File\Upload\Methods;

use Next\Components\Object;                                        # Object Class

use Next\Validation\Validator;                                     # Validator Interface

use Next\Validation\Chain as ValidatorsChain;                      # Validator Chain
use Next\File\Upload\PostProcessor\Chain as PostProcessorChain;    # Post-Processor Chain

use Next\File\Upload\PostProcessor\PostProcessor;                  # Post-Processor Interface
use Next\File\Upload\Handler as UploadHandler;                     # Upload Handler Class

/**
 * Defines a base structure for File Uploading through different Request Methods
 *
 * @package    Next\File\Upload
 */
abstract class AbstractMethod extends Object implements Method {

    /**
     * Upload Handler
     *
     * @var \Next\File\Upload\Handler $handler
     */
    protected $handler;

    /**
     * Validators Chain
     *
     * @var \Next\Validation\Chain $validators
     */
    protected $validators;

    /**
     * Upload Post Processors CHain
     *
     * @var \Next\File\Upload\PostProcessor\Chain $postProcessors
     */
    protected $postProcessors;

    /**
     * Files successfully uploaded
     *
     * @var array $succeed
     */
    protected $succeed    = [];

    /**
     * Files unsuccessfully uploaded
     *
     * @var array $failed
     */
    protected $failed     = [];

    /**
     * Upload Method Constructor
     *
     * @param \Next\File\Upload\Handler $handler
     *   Upload Handler
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for each Upload Method
     */
    public function __construct( UploadHandler $handler, $options = NULL ) {

        $this -> handler           = $handler;

        $this -> validators        = new ValidatorsChain;
        $this -> postProcessors    = new PostProcessorChain;

        parent::__construct( $options );
    }

    // Method Interface Methods Implementations

    /**
     * Add an external Validator to the Chain
     *
     * @param \Next\Validation\Validator $validator
     *  External Validator
     *
     * @return \Next\File\Upload\Methods\Method
     *  Upload Method Object (Fluent-Interface)
     */
    public function addValidator( Validator $validator ) {

        $this -> validators -> add( $validator );

        return $this;
    }

    /**
     * Get Validators Chain
     *
     * @return \Next\Validation\Chain
     *  Validators Chain
     */
    public function getValidators() {
        return $this -> validators;
    }

    /**
     * Add an Upload Post-Processor
     *
     * @param \Next\File\Upload\PostProcessor $processor
     *  Upload Post-Processor
     *
     * @return \Next\File\Upload\Methods\Method
     *  Upload Method Object (Fluent-Interface)
     */
    public function addPostProcessor( PostProcessor $processor ) {

        $this -> postProcessors -> add( $processor );

        return $this;
    }

    /**
     * Get Post-Processors Chain
     *
     * @return \Next\File\Upload\PostProcessor\Chain
     *  Post-Processors Chain
     */
    public function getPostProcessors() {
        return $this -> postProcessors;
    }

    /**
     * Get files successfully uploaded
     *
     * @return array
     *  Files successfully uploaded
     */
    public function getSucceed() {
        return $this -> succeed;
    }

    /**
     * Get files unsuccessfully uploaded
     *
     * @return array
     *  Files unsuccessfully uploaded
     */
    public function getFailed() {
        return $this -> failed;
    }
}