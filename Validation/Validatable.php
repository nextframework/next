<?php

/**
 * Validatable Interface | Validation\Validatable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation;

/**
 * Validatable Objects can be validated against a set of rules
 *
 * The Validatable Interface has the same purpose of the Validator Interface,
 * they only differ in lexical purposes
 *
 * @package    Next\Validation
 */
interface Validatable {

    /**
     * Validates given data
     */
    public function validate();
}
