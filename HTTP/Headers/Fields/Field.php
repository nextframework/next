<?php

/**
 * HTTP Header Fields Interface | HTTP\Headers\Fields\Field.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Fields;

/**
 * Headers Fields Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Field {

    /**
     * Set Header Value
     *
     * @param string $value
     *  Header Value
     */
    public function setValue( $value );

    /**
     * Get Header Name
     */
    public function getName();

    /**
     * Get Header Value
     */
    public function getValue();
}
