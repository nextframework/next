<?php

/**
 * HTTP Stream Interface | HTTP\Stream\Stream.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream;

/**
 * Stream Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Stream {

    /**
     * Get Adapter Object
     */
    public function getAdapter();

    /**
     * Get Stream Resource
     */
    public function getStream();
}
