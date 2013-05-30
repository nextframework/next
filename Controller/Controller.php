<?php

namespace Next\Controller;

/**
 * Controller Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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
