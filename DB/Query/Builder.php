<?php

namespace Next\DB\Query;

use Next\DB\Query\Renderer\Renderer;    # Query Renderer
use Next\Components\Utils\ArrayUtils;   # Array Utils Class
use Next\Components\Object;             # Object Class

/**
 * Query Builder Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Builder extends Object implements Query {

    /**
     * Query Renderer
     *
     * @var Next\DB\Query\Renderer\Renderer $renderer
     */
    protected $renderer;

    /**
     * Built Query
     *
     * @var string $query
     */
    protected static $query;

    /**
     * Placeholders Replacements
     *
     * @var array $replacements
     */
    public static $replacements = array();

    /**
     * SQL Statement Components
     *
     * @var array $parts
     */
    protected static $parts = array();

    /**
     * Query Builder Constructor
     *
     * @param Next\DB\Renderer\Renderer $renderer
     *   Query Renderer
     */
    public function __construct( Renderer $renderer ) {

        // Setting up Resources

        $this -> renderer =& $renderer;

        // Reset and Prepare SQL Parts Structure

        $this -> reset();

        // Extra Initialization

        $this -> init();
    }

    /**
     * Additional Initialization. Must be overwritten
     */
    protected function init() {}

    // Query Builder Methods

    /**
     * Add WHERE Clause(s)
     *
     * @param array|string $condition
     *   WHERE Clause
     *
     * @param string|array|optional $value
     *   Value for Clauses Placeholders (if any)
     *
     * @param boolean|optional $isMatchingClause
     *
     *   <p>If TRUE it will be an 'AND' WHERE Clause</p>
     *
     *   <p>If FALSE, it will be an 'OR' WHERE Clause</p>
     *
     * @return Next\DB\Query\Builder
     *   Builder Instance (Fluent Interface)
     */
    public function where( $condition, $value = NULL, $isMatchingClause = TRUE ) {

        // Registering Placeholders Replacement Values (if any)

        if( ! is_null( $value ) ) {

            // If we have an array of values...

            if( is_array( $value ) ) {

                foreach( $value as $v ) {

                    // ... all them will be considered as OR WHERE Clause...

                    $this -> orWhere( $condition, $v );
                }

                // ... and the AND WHERE invoked will be ignored

                return $this;
            }

            $this -> setReplacements( (array) $value, self::SQL_WHERE );
        }

        $condition = trim( $condition );

        // Adding Clause to SQL Parts Property

        self::$parts[ self::SQL_WHERE ][] = array(

            $condition => ( $isMatchingClause ? self::SQL_AND : self::SQL_OR )
        );

        return $this;
    }

    /**
     * Wrapper for OR WHERE Clause
     *
     * @param array|string $condition
     *   WHERE Condition
     *
     * @param string|array|optional $value
     *   Value for Clause's Placeholders (if any)
     *
     * @return Next\DB\Query\Builder
     *   Builder Instance (Fluent Interface)
     */
    public function orWhere( $condition, $value = NULL ) {
        return $this -> where( $condition, $value, FALSE );
    }

    /**
     * Add ORDER BY Clause(s)
     *
     * @param string|array $field
     *
     *   <p>As string, field to lead the ordenation.</p>
     *
     *   <p>
     *       As associative array, keys are the fields and values
     *       ordenation types
     *   </p>
     *
     * @param string|optional $type
     *
     *   <p>Type of ordenation: ASC or DESC.</p>
     *
     *   <p>
     *       If <strong>$field</strong> is an array, this value is not
     *       immediately used
     *   </p>
     *
     * @return Next\DB\Query\Builder
     *   Builder Instance (Fluent Interface)
     *
     * @throws Next\DB\Query\QueryException
     *   Ordenation field is empty or was considered an empty string
     */
    public function order( $field, $type = self::SQL_ORDER_ASC ) {

        // Do we have multiple ORDER BY clause?

        if( is_array( $field ) ) {

            if( is_array( $type ) ) {

                // Both arguments are arrays, let's equalize their lengths...

                ArrayUtils::equalize( $field, $type );

                // ... and combine to make recursion easily

                $field = array_combine( $field, $type );
            }

            /**
             * @internal
             * Only first argument is array, so we expect it to be
             * an associative pair/value as field to order => ordenation type
             */
            foreach( $field as $f => $t ) {

                $this -> order( $f, $t );
            }

        } else {

            // Ensuring we have a Field to lead the ordenation

            $field = trim( (string) $field );

            if( empty( $field ) ) {

                throw QueryException::logic(

                    'Field to order results must be set as non-empty string'
                );
            }

            // Ensuring we have a ordening direction (even optional for most RDBMS)

            $type = trim( (string) $type );

            if( empty( $type ) ) {

                $type = self::SQL_ORDER_ASC;
            }

            /**
             * @internal
             * ORDER BY Clause cannot be "prepared", at least not as a question
             * mark placeholder, because for some reason PDO::prepare() will
             * try to wrap the values (field and ordenation type) into quotes,
             * invalidating this specific part of SQL Statement
             */
            self::$parts[ self::SQL_ORDER_BY ][ $field ] = $type;
        }

        return $this;
    }

    /**
     * Add a LIMIT Clause, with or without an offset
     *
     * @param integer|optional $limit
     *   Number of records to be returned
     *
     * @param integer|optional $offset
     *   Record to start
     *
     * @return Next\DB\Query\Builder
     *   Builder Instance (Fluent Interface)
     */
    public function limit( $limit = 1, $offset = 0 ) {

        if( array_key_exists( self::SQL_LIMIT, self::$parts ) &&
                self::$parts[ self::SQL_LIMIT ] !== FALSE ) {

            return NULL;
        }

        $limit  = (int) $limit;
        $offset = (int) $offset;

        $offset = ( $offset >= 0 ? $offset : 0 );

        // Registering Placeholder Replacement Values

        self::$parts[ self::SQL_LIMIT ] = array( $offset, ( $limit > 0 ? $limit : 1 ) );

        return $this;
    }

    // Accessors

    /**
     * Reset Builder Data
     *
     * <p>Useful for consecutive operations (like in a loop)</p>
     *
     * @return Next\DB\Query\Builder
     *   Builder Instance (Fluent Interface)
     */
    public function reset() {

        // Reseting all Clauses Structure

        self::$parts = array(

            self::SQL_DISTINCT      => FALSE,
            self::SQL_WHERE         => array(),
            self::SQL_GROUP_BY      => FALSE,
            self::SQL_ORDER_BY      => FALSE,
            self::SQL_LIMIT         => FALSE,
            self::SQL_HAVING        => array(),
            self::SQL_UNION         => NULL
        );

        // Placeholders Replacements

        self::$replacements = array(

            self::SQL_WHERE     => array(),
            self::SQL_HAVING    => array(),
            self::SQL_GROUP_BY  => array()
        );

        // Built Query

        self::$query = NULL;

        return $this;
    }

    /**
     * Reset Part of Builder Data
     *
     * <p>Useful to fix user mistakes when a Table Manager is required</p>
     *
     * @param string $part
     *   A specific part to reset
     *
     * @return Next\DB\Query\Builder
     *   Builder Instance (Fluent Interface)
     */
    public function resetPart( $part ) {

        if( array_key_exists( $part, self::$parts ) ) {

            unset( self::$parts[ $part ] );
        }

        return $this;
    }

    /**
     * Get Query Component
     *
     * @param string $part
     *   Part of SQL Builder to be retrieved
     *
     * @return mixed|NULL
     *   Desired SQL Part if present and NULL otherwise
     */
    public function getPart( $part ) {
        return ( array_key_exists( $part, self::$parts ) ? self::$parts[ $part ] : NULL );
    }

    /**
     * Get all Query Components
     *
     * @return array
     */
    public function getParts() {
        return self::$parts;
    }

    /**
     * Create a query from external context
     *
     * @param string $query
     *   Query to create
     *
     * @return Next\DB\Query\Builder
     *   Builder Instance (Fluent Interface)
     */
    public function createQuery( $query ) {

        self::$query =& $query;

        return $this;
    }

    /**
     * Define Placeholders Replacements from external context
     *
     * @param array $replacements
     *   Query Replacements
     *
     * @param string|optional $group
     *   Group where store the replacements
     *
     * @return Next\DB\Query\Builder
     *   Builder Instance (Fluent Interface)
     */
    public function setReplacements( array $replacements, $group = NULL ) {

        if( ! is_null( $group ) ) {

            self::$replacements[ $group ] = array_merge(
                self::$replacements[ $group ], array_values( $replacements )
            );

        } else {

            self::$replacements = array_merge(
                self::$replacements, array_values( $replacements )
            );
        }

        return $this;
    }

    /**
     * Get Placeholder Replacements
     *
     * @param string|optional $group
     *  A specific group of replacements
     *
     * @return array
     *   SQL Placeholders Replacements
     */
    public function getReplacements( $group = NULL, $raw = FALSE ) {

        if( ! is_null( $group ) && array_key_exists( $group, self::$replacements ) ) {

            $replacements = self::$replacements[ $group ];

        } else {

            $replacements = self::$replacements;
        }

        // Should we return the Replacements structure "as is"?

        if( $raw ) {
            return $replacements;
        }

        // Well, let's "unidimensionalize" (yeah, I invented this) it then

        $r = array();

        array_walk_recursive(

            $replacements,

            function( $current ) use( &$r ) {

                $r[] =& $current;
            }
        );

        return $r;
    }

    /**
     * Assemble the Query
     *
     * @return string
     *   Built Query
     */
    public function assemble() {

        // Assembling SQL Statement Parts, if needed

            // DISTINCT Clause

        if( array_key_exists( self::SQL_DISTINCT, self::$parts ) ) {

            self::$query = $this -> renderer -> distinct(

                self::$query, self::$parts[ self::SQL_DISTINCT ]
            );
        }

            // WHERE Clause

        if( array_key_exists( self::SQL_WHERE, self::$parts ) ) {

            $clauses  = $this -> renderer -> where(

                self::$parts[ self::SQL_WHERE ]
            );
        }

            // GROUP BY Clause

        if( array_key_exists( self::SQL_GROUP_BY, self::$parts ) ) {

            if( self::$parts[ self::SQL_GROUP_BY ] ) {
                $clauses .= $this -> renderer -> group();
            }
        }


            // HAVING Clause

        if( array_key_exists( self::SQL_HAVING, self::$parts ) ) {

            $clauses .= $this -> renderer -> having(

                self::$parts[ self::SQL_HAVING ]
            );
        }

        /**
         * @internal
         * ORDER BY Clause
         *
         * This is little different than the others because PDO::prepare() will
         * try to wrap our question mark placeholder used to specify the ASC / DESC
         * terms, in quotes, invalidating the SQL
         */
        if( array_key_exists( self::SQL_ORDER_BY, self::$parts ) ) {

            if( self::$parts[ self::SQL_ORDER_BY ] !== FALSE ) {

                $clauses .= $this -> renderer -> order(

                    self::$parts[ self::SQL_ORDER_BY ]
                );
            }
        }

            // LIMIT Clause

        // Same consideration above

        if( array_key_exists( self::SQL_LIMIT, self::$parts ) ) {

            if( self::$parts[ self::SQL_LIMIT ] ) {
                $clauses .= $this -> renderer -> limit( self::$parts[ self::SQL_LIMIT ] );
            }
        }

        // Removing the last blank space (for presentation purposes only) and building full SQL

        return rtrim( sprintf( '%s %s', self::$query, $clauses ) );
    }
}