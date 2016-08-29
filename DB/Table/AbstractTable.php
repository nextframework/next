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

    // Interface Methods Implementation

    /**
     * Get Table Name
     * @TODO: Rename this to getTableName() and replace the rest of occurrences
     * @return string
     *  Table Name
     */
    public function getTable() {
        return ( ! is_null( $this -> _table ) ? $this -> _table : strtolower( $this ) );
    }

    /**
     * Get Primary Key
     *
     * @return string
     *  Primary Key Column
     */
    public function getPrimaryKey() {
         return $this -> _primary;
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
            \ReflectionProperty::IS_PROTECTED
        );

        // Filtering properties

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

        /*$cross = sprintf( '_%s', $column );

        if( property_exists( $this, $cross ) ) {

            $this -> {$cross} = $value;

        } else {*/
            $this -> {$column} = $value;
        //}
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
