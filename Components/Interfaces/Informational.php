<?php

/**
 * Informational Components Interface | Components\Interfaces\Informational.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Interfaces;

/**
 * Informational Objects provides information messages about their
 * processing state, success, error or just neutral messages
 *
 * @package    Next\Components\Interfaces
 */
interface Informational {

    /**
     * Get success message
     */
    public function getSuccessMessage();

    /**
     * Get error message
     */
    public function getErrorMessage();

    /**
     * Get informational message
     */
    public function getInformationalMessage();
}