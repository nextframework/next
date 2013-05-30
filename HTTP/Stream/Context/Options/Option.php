<?php

namespace Next\HTTP\Stream\Context\Options;

/**
 * Stream Context Options Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Option {

    /**
     * Check if Context Option is acceptable by wrapper
     *
     * @param string $option
     *   Context Option to be checked
     */
    public function accept( $option );

    /**
     * Get Wrapper Name
     */
    public function getWrapperName();
}
