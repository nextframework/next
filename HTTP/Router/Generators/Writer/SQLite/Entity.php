<?php

/**
 * Routes Generator SQLite Output Writer Entity Class | HTTP\Router\Generators\Writer\SQLite\Entity.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Router\Generators\Writer\SQLite;

use Next\DB\Entity\AbstractEntity;    # Entity Abstract Class

/**
 * Annotations Routes Generator: SQLite Annotations Entity Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 *
 * @uses          \Next\DB\Entity\AbstractEntity
 */
class Entity extends AbstractEntity {

    /**
     * Primary Key Column
     *
     * @var string $_primary
     */
    protected $_primary = 'routeID';

    /**
     * Entity Name
     *
     * @var string $_entity
     */
    protected $_entity = 'routes';

    /**
     * Request Method (GET, POST...)
     *
     * @var string $requestMethod
     */
    protected $requestMethod;

    /**
     * Application classname the Controller belongs to
     *
     * @var string $application
     */
    protected $application;

    /**
     * Controller Classname
     *
     * @var string $controller
     */
    protected $controller;

    /**
     * Controller's Action Method
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