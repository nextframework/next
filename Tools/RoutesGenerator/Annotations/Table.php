<?php

namespace Next\Tools\RoutesGenerator\Annotations;

/**
 * Annotations Routes Generator: Annotations Table Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Table extends \Next\DB\Table\AbstractTable {

    /**
     * Table Name
     *
     * @var string $_table
     */
    protected $_table = 'routes';

    /**
     * Route ID
     *
     * @var integer $routeID
     */
    protected $routeID;

    /**
     * Request Method (GET, POST...)
     *
     * @var string $requestMethod
     */
    protected $requestMethod;

    /**
     * Controller Application
     *
     * @var string $application
     */
    protected $application;

    /**
     * Controller Class
     *
     * @var string $class
     */
    protected $class;

    /**
     * Controller Action Method
     *
     * @var string $method
     */
    protected $method;

    /**
     * Route URI
     *
     * @var string $URI
     */
    protected $URI;

    /**
     * Route Required Parameters
     *
     * @var string $requiredParams
     */
    protected $requiredParams;

    /**
     * Route Optional Parameters
     *
     * @var string $optionalParams
     */
    protected $optionalParams;
}
