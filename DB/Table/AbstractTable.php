<?php

/**
 * Database Table Abstract Class | DB\Table\AbstractTable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Table;

use Next\Components\Object;    # Object Class

/**
 * Table Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractTable extends Object implements Table {

    /**
     * Table Name (Optional)
     *
     * @var string $_table
     */
    protected $_table;

    /**
     * Primary Key Column
     *
     * @var string $_primary
     */
    protected $_primary;

    /**
     * Table Constructor
     *
     * Checks for the presence of model PRIMARY KEY and thus aborting if missing
     *
     * @throws \Next\DB\Table\TableException
     *  Thrown if the PRIMARY KEY column NAME is missing from Entity
     */
    public function init() {

        if( is_null( $this -> _primary ) ) {
            throw TableException::missingPrimaryKey( $this );
        }

        // Creating the PRIMARY KEY Column in runtime

        $this -> {$this -> _primary} = NULL;
    }

    // Interface Methods Implementation

    /**
     * Get Table Name
     *
     * @return string
     *  Table Name
     */
    public function getTableName() {
        return ( ! is_null( $this -> _table ) ? $this -> _table : strtolower( $this ) );
    }

    /**
     * Get Primary Key
     *
     * @return integer
     *  Primary Key Column COLUMN
     */
    public function getPrimaryKey() {
         return $this -> _primary;
    }

    /**
     * Set PRIMARY KEY value
     *
     * @param integer|mixed $pk
     *  PRIMARY KEY value
     *
     * @return \Next\SB\Table\Table
     *  Table Object (Fluent Interface)
     */
    public function setPrimaryKey( $pk ) {

        $this -> {$this -> _primary} = $pk;

        return $this;
    }

    /**
     * List Table Fields
     *
     * @param boolean|optional $ignorePrefixed
     *  Defines whether or not properties with a leading underscore
     *  (i.e. $_property) will also be ignored when listing Table Fields.
     *  Defaults to TRUE
     *
     * @return array
     *  Modified Table Fields
     */
    public function getFields( $ignorePrefixed = TRUE ) {

        // Saving Entity Context in order to use it inside Closure Scope

        $context = $this;

        /**
         * @internal
         * Listing Properties
         *
         * To be included in the list, properties must be protected and
         * must not start with an underscore
         */
        $properties = $this -> getClass() -> getProperties(
            \ReflectionProperty::IS_PUBLIC + \ReflectionProperty::IS_PROTECTED
        );

        $properties = array_filter(

            $properties,

            function( \ReflectionProperty $property ) use( $ignorePrefixed ) {

                $shouldReturn = TRUE;

                if( $property -> class == 'Next\Components\Object' )    $shouldReturn = FALSE;
                if( $property -> class == 'Next\Components\Context' )   $shouldReturn = FALSE;
                if( $property -> class == 'Next\Components\Prototype' ) $shouldReturn = FALSE;

                if( $ignorePrefixed && substr( $property -> name, 0, 1 ) == '_' ) {
                    $shouldReturn = FALSE;
                }

                return $shouldReturn;
            }
        );

        $fields = array();

        // Building Fields Structure

        array_walk(

            $properties,

            function( $property ) use( &$fields, $context ) {

                // Enable access to protected properties

                $property -> setAccessible( TRUE );

                $fields[ $property -> getName() ] = $property -> getValue( $context );
            }
        );

        return $fields;
    }

    // ArrayAccess Methods Implementation

    /**
     * Checks whether or not a Column at given offset exists
     *
     * @param mixed|string|integer $offset
     *  Column offset to be checked for
     *
     * @return boolean
     *  TRUE if given column offset exists and FALSE otherwise
     */
    public function offsetExists( $offset ) {
        return property_exists( $this, $offset );
    }

    /**
     * Returns the value of given a Column at given offset
     *
     * @param mixed|string|integer $offset
     *  Column offset to be retrieved
     *
     * @return mixed
     *  Data stored in given Table Column
     *
     * @see AbstractTable::getPrimaryKey()
     */
    public function offsetGet( $offset ) {

        if( $offset == $this -> _primary ) {
            return $this -> getPrimaryKey( $value ); return;
        }

        return $this -> {$offset};
    }

    /**
     * Assigns a value to a Column at given offset
     *
     * @param mixed|string|integer $offset
     *  Column offset to where assign data to
     *
     * @param mixed $value
     *  Data do assign
     *
     * @see AbstractTable::setPrimaryKey()
     */
    public function offsetSet( $offset, $value ) {

        if( $offset == $this -> _primary ) {
            $this -> setPrimaryKey( $value ); return;
        }

        $this -> {$offset} = $value;
    }

    /**
     * Empties the contents of a Column at given offset
     *
     * @param mixed|string|integer $offset
     *  Column offset to be emptied
     *
     * @return void
     */
    public function offsetUnset( $offset ) {
        $this -> {$offset} = NULL;
    }

    // Overloading

    /**
     * Assigns a value to a Column at given offset
     *
     * @param mixed|string|integer $offset
     *  Column offset to where assign data to
     *
     * @param mixed $value
     *  Data do assign
     *
     * @see AbstractTable::offsetSet()
     */
    public function __set( $offset, $value ) {
        $this -> offsetSet( $offset, $value );
    }

    /**
     * Get column value at given offset
     *
     * @param mixed|string|integer $offset
     *  Column offset to be retrieved
     *
     * @return mixed
     *  Data stored in given Table Column
     *
     * @see AbstractTable::offsetGet()
     */
    public function __get( $offset ) {
        return $this -> offsetGet( $offset );
    }
}
