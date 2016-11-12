<?php

namespace Next\DB\Table;

/**
 * Table Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Table extends \ArrayAccess {

    /**
     * Get Entity Name
     */
    public function getTableName();

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
