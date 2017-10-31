<?php

/**
 * HTTP Stream Context Options Interface | HTTP\Stream\Context\Options\Option.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream\Context\Options;

/**
 * The Stream Context Options Type
 *
 * @package    Next\HTTP
 *
 * @deprecated
 *
 * @FIXME
 */
interface Option {

    /**
     * Check if Context Option is acceptable by wrapper
     *
     * @param string $option
     *  Context Option to be checked
     */
    public function accept( $option );

    /**
     * Get Wrapper Name
     */
    public function getWrapperName();
}
