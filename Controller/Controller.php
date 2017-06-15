<?php

/**
 * Controller Interface | Controller\Controller.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
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
