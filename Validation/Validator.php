<?php

/**
 * Validator Interface | Validation\Validator.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Validation;

/**
 * Validator Objects validate input data
 *
 * The Validator Interface has the same purpose of the Validatable Interface,
 * they only differ in lexical purposes
 *
 * @package    Next\Validation
 *
 * @uses       Next\Validation\Validatable
 */
interface Validator extends Validatable {}
