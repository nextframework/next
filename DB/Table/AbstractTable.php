<?php

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
     * @throws Next\DB\Table\TableException
     *  Thrown if the PRIMARY KEY column NAME is missing from Entity
     */
    public function __construct() {

        parent::__construct();

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
     * @return Next\SB\Table\Table
     *  Table Object (Fluent Interface)
     */
    public function setPrimaryKey( $pk ) {

        $this -> {$this -> _primary} = (integer) $pk;

        return $this;
    }

    /**
     * List Table Fields
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
     * Checks whether or not a Table Column exists
     *
     * @param  mixed|string|integer $column
     *  Table Column to look for
     *
     * @return boolean
     *  TRUE if given column exists and FALSE otherwise
     */
    public function offsetExists( $column ) {
        return property_exists( $this, $column );
    }

    /**
     * Returns the value of given Table Column
     *
     * @param  mixed|string|integer $column
     *  Table Column to look for
     *
     * @return mixed
     *  Data stored in given Table Column
     */
    public function offsetGet( $column ) {
        return $this -> {$column};
    }

    /**
     * Assigns a value to a Table Column
     *
     * @param  mixed|string|integer $column
     *  Table Column to assign data to
     *
     * @param mixed $value
     *  Data do assign
     *
     * @return void
     */
    public function offsetSet( $column, $value ) {
        $this -> {$column} = $value;
    }

    /**
     * Empties the contents of given Table Column
     *
     * Note: This, obviously, won't destroy the property
     *
     * @param  mixed|string|integer $column
     *  Table Column to be emptied
     *
     * @return void
     */
    public function offsetUnset( $offset ) {
        $this -> {$field} = NULL;
    }

    // Overloading

    /**
     * Set field value
     *
     * @note Plain and simple! No treatment will be applied!
     *
     * @param string $field
     *  Table Field
     *
     * @param mixed $value
     *  Field Value
     */
    public function __set( $field, $value ) {
        $this -> {$field} = $value;
    }

    /**
     * Get field value
     *
     * @param string $field
     *  Table Field
     *
     * @param mixed $value
     *  Field Value
     */
    public function __get( $field ) {
        return ( isset( $this -> {$field} ) ? $this -> {$field} : NULL );
    }
}
