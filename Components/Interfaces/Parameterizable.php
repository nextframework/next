<?php

/**
 * Parameterizable Component Interface | Components\Interfaces\Parameterizable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Interfaces;

/**
 * Parameterizable Objects make use of \Next\Components\Parameter
 * to provide a way to customize classes functionality with
 * Default, Common and Use Options
 *
 * @package    Next\Components\Interfaces
 */
interface Parameterizable {

    /**
     * Set Child Classes Options
     */
    public function setOptions();

    /**
     * Get Class Options
     */
    public function getOptions();
}