<?php

/**
 * HTTP Response Headers Class | HTTP\Headers\ResponseHeaders.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\HTTP\Headers;

/**
 * Next Header Interface
 */
use Next\HTTP\Headers\Fields\Field;

/**
 * Response Headers Interface
 */
use Next\HTTP\Headers\Fields\Request;

/**
 * HTTP Response Headers Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ResponseHeaders extends AbstractHeaders {

    // Abstract Methods Implementation

    /**
     * Check for Header Field acceptance
     *
     * To be valid, a Response Header must NOT implement Request Header Interface
     *
     * This reverse logic is because there are two categories, Common and Entity,
     * with headers that can be common to Request and/or Response at same time
     *
     * @param \Next\HTTP\Headers\Fields\Field $field
     *
     *  Header Field Object to have its acceptance in Response Headers Lists Collection checked
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Response Headers Collection and FALSE otherwise
     */
    protected function accept( Field $field ) {

        return ( ! $field instanceof Request );
    }
}