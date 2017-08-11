<?php

/**
 * Controller Interface | Controller\Controller.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller;

/**
 * Defines the Controller Type, with all methods that must be present
 * in an Controller, be it through \Next\Controller\AbstractController
 * or the concrete implementations of it
 *
 * @package    Next\Controller
 */
interface Controller {

    /**
     * Get Request Object
     */
    public function getRequest();

    /**
     * Get Response Object
     */
    public function getResponse();
}
