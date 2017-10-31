<?php

/**
 * Verifiable Interface | Validation\Verifiable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation;

/**
 * Verifiable Objects provide a way to verify its integrity
 *
 * @package    Next\Validation
 */
interface Verifiable {

    /**
     * Verifies Object Integrity
     */
    public function verify();
}