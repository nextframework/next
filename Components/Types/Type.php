<?php

/**
 * Types Component Interface | Components\Types\Type.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Types;

use Next\Components\Interfaces\Prototypable;    # Prototypable Resource Class

/**
 * Defines the Data-type Type, with all methods that must be present
 * in an Data-type, be it through \Next\Components\Types\AbstractTypes
 * or the concrete implementations of it
 *
 * @package    Next\Components\Types
 */
interface Type extends Prototypable {}