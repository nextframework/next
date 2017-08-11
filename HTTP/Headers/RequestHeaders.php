<?php

/**
 * HTTP Request Headers Class | HTTP\Headers\RequestHeaders.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers;

use Next\HTTP\Headers\Fields\Field;       # Header Fields Interface
use Next\HTTP\Headers\Fields\Response;    # Response Header Fields Interface

/**
 * HTTP Request Headers Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class RequestHeaders extends AbstractHeaders {

    // Abstract Methods Implementation

    /**
     * Check for Header Field acceptance
     *
     * To be valid, a Request Header must NOT implement Response Header Interface
     *
     * This reverse logic is because there are two categories, Common and Entity,
     * with headers that can be common to Request and/or Response at same time
     *
     * @param \Next\HTTP\Headers\Fields\Field $field
     *
     *  Header Field Object to have its acceptance in Request Headers Lists Collection checked
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Request Headers Collection and FALSE otherwise
     */
    protected function accept( Field $field ) {

        return ( ! $field instanceof Response );
    }
}
