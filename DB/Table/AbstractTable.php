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

    // Interface Methods Implementation

    /**
     * Get Table Name
     *
     * @return string
     *   Table Name
     */
    public function getTable() {
        return ( ! is_null( $this -> _table ) ? $this -> _table : strtolower( $this ) );
    }

    /**
     * List Table Fields
     *
     * @return array
     *   Modified Table Fields
     */
    public function getFields() {

        // Saving Entity Context in order to use it inside Closure Scope

        $context = $this;

        /**
         * @internal
         * Listing Properties
         *
         * To be included in the list, properties must be protected, not NULL
         * and must not start with an underscore
         */
        $properties = $this -> getClass()
                            -> getProperties( \ReflectionProperty::IS_PROTECTED );

        $fields = array();

        // Building Fields Structure

        array_walk(

            $properties,

            function( $property ) use( &$fields, $context ) {

                // Enable access to protected properties

                $property -> setAccessible( TRUE );

                $name  = $property -> getName();
                $value = $property -> getValue( $context );

                // Filtering properties in according to conditions mentioned

                if( ! is_null( $value ) && substr( $name, 0, 1 ) !== '_' ) {

                    $fields[ $name ] = $value;
                }
            }
        );

        return $fields;
    }

    // Overloading

    /**
     * Set field value
     *
     * @note Plain and simple! No treatment will be applied!
     *
     * @param string $field
     *   Table Field
     *
     * @param mixed $value
     *   Field Value
     */
    public function __set( $field, $value ) {

        $this -> {$field} = $value;
    }
}
