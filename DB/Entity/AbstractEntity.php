<?php

/**
 * Database Entity Abstract Class | DB\Entity\AbstractEntity.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Entity;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Object;    # Object Class

/**
 * Entity Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractEntity extends Object implements Entity {

    /**
     * Optional Entity Name
     *
     * @var string $_entity
     */
    protected $_entity;

    /**
     * Primary Key Column
     *
     * @var string $_primary
     */
    protected $_primary;

    /**
     * Additional Initialization.
     * Checks for the presence of a PRIMARY KEY on Entity Class,
     * aborting if missing
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if the PRIMARY KEY column NAME has not been defined
     *  on Entity Class
     *
     * @todo Implement Verifiable Interface for this
     */
    public function init() {

        if( $this -> _primary === NULL ) {

            throw new InvalidArgumentException(

                sprintf(

                    'PRIMARY KEY has not been defined on
                    Entity Class <strong>%s</strong> (<em>%s</em>)',

                    (string) $this, $this -> getClass() -> getName()
                )
            );
        }

        // Creating the PRIMARY KEY Column in runtime

        $this -> {$this -> _primary} = NULL;
    }

    // Interface Methods Implementation

    /**
     * Get Entity Name
     *
     * @return string
     *  Entity Name if manually defined or a lowercased version of
     *  Entity Class' short name otherwise
     */
    public function getEntityName() {
        return ( $this -> _entity !== NULL ? $this -> _entity : strtolower( $this ) );
    }

    /**
     * Get Primary Key
     *
     * @return integer
     *  Primary Key Column Column name, not the value
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
     * @return \Next\DB\Entity\Entity
     *  Entity Object (Fluent Interface)
     */
    public function setPrimaryKey( $pk ) {

        $this -> {$this -> _primary} = $pk;

        return $this;
    }

    /**
     * List Entity Fields
     *
     * @param boolean|optional $ignorePrefixed
     *  Defines whether or not properties with a leading underscore
     *  (i.e. $_property) will also be ignored when listing Entity Fields.
     *  Defaults to TRUE
     *
     * @return array
     *  Modified Entity Fields
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

                if( $property -> class == 'Next\Components\Object' ||
                        $property -> class == 'Next\Components\Context' ||
                            $property -> class == 'Next\Components\Prototype' ) {

                    $shouldReturn = FALSE;
                }

                if( $ignorePrefixed && substr( $property -> name, 0, 1 ) == '_' ) {
                    $shouldReturn = FALSE;
                }

                return $shouldReturn;
            }
        );

        $fields = [];

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
     * Checks whether or not an Entity Column exists
     *
     * @param mixed|string|integer $column
     *  Entity Column to be checked for
     *
     * @return boolean
     *  TRUE if given Entity Column exists and FALSE otherwise
     */
    public function offsetExists( $column ) {
        return property_exists( $this, $column );
    }

    /**
     * Returns the value of an Entity Column
     *
     * @param mixed|string|integer $offset
     *  Entity Column to be retrieved
     *
     * @return mixed
     *  Data stored at given Entity Column
     *
     * @see AbstractEntity::getPrimaryKey()
     */
    public function offsetGet( $offset ) {

        if( $offset == $this -> _primary ) {
            return $this -> getPrimaryKey( $value ); return;
        }

        return $this -> {$offset};
    }

    /**
     * Assigns a value to an Entity Column
     *
     * @param mixed|string|integer $offset
     *  Column offset to where assign data to
     *
     * @param mixed $value
     *  Data do assign
     *
     * @see AbstractEntity::setPrimaryKey()
     */
    public function offsetSet( $offset, $value ) {

        if( $offset == $this -> _primary ) {
            $this -> setPrimaryKey( $value ); return;
        }

        $this -> {$offset} = $value;
    }

    /**
     * Empties the contents of an Entity Column
     *
     * @param mixed|string|integer $offset
     *  Entity Column to be emptied
     */
    public function offsetUnset( $offset ) {
        $this -> {$offset} = NULL;
    }

    // Overloading

    /**
     * Assigns a value to an Entity Column
     *
     * @param mixed|string|integer $offset
     *  Column offset to where assign data to
     *
     * @param mixed $value
     *  Data do assign
     *
     * @see AbstractEntity::offsetSet()
     */
    public function __set( $offset, $value ) {
        $this -> offsetSet( $offset, $value );
    }

    /**
     * Returns the value of an Entity Column
     *
     * @param mixed|string|integer $offset
     *  Entity Column to be retrieved
     *
     * @return mixed
     *  Data stored at given Entity Column
     *
     * @see AbstractEntity::offsetGet()
     */
    public function __get( $offset ) {
        return $this -> offsetGet( $offset );
    }
}
