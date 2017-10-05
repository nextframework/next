<?php

/**
 * Database Entity Interface | DB\Entity\Entity.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Entity;

/**
 * Entity Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Entity extends \ArrayAccess {

    /**
     * Get Entity Name
     */
    public function getEntityName();

    /**
     * Get Primary Key COLUMN
     */
    public function getPrimaryKey();

    /**
     * Set PRIMARY KEY value
     *
     * @param integer|mixed $pk
     *  PRIMARY KEY value
     */
    public function setPrimaryKey( $pk );

    /**
     * List Entity Fields
     */
    public function getFields();
}
