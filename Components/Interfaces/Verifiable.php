<?php

/**
 * Verifiable Interface | Components\Interfaces\Verifiable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Interfaces;

/**
 * Verifiable Objects are assumed to provide a way to verify its integrity
 *
 * @package    Next\Components\Interfaces
 */
interface Verifiable {

    /**
     * Verify Object Integrity
     */
    public function verify();
}