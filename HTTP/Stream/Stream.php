<?php

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
