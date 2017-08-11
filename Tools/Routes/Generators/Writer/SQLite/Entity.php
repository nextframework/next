<?php

/**
 * Routes Generator SQLite Output Writer Entity Class | Tools\Routes\Generators\Writer\SQLite\Entity.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Tools\Routes\Generators\Writer\SQLite;

use Next\DB\Table\AbstractTable;

/**
 * Annotations Routes Generator: SQLite Annotations Entity Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 *
 * @uses          \Next\DB\Table\AbstractTable
 */
class Entity extends AbstractTable {

    /**
     * Primary Key Column
     *
     * @var string $_primary
     */
    protected $_primary = 'routeID';

    /**
     * Table Name
     *
     * @var string $_table
     */
    protected $_table = 'routes';

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
     * @var string $controller
     */
    protected $controller;

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
