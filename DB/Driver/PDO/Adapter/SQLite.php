<?php

/**
 * PDO Driver Adapter: SQLite | DB\Driver\PDO\Adapter\SQLite.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Driver\PDO\Adapter;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\RuntimeException;
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\DB\Driver\PDO\AbstractPDO;              # PDO Abstract Class
use Next\DB\Query\Renderer\MySQL as Renderer;    # MySQL Query Renderer Class

/**
 * The PDO SQLite Connector.
 * Defines the DSN PDO will use and which Query Renderer to render
 * the Query Clauses
 *
 * @package    Next\DB
 *
 * @uses       Next\Exception\Exceptions\RuntimeException
 *             Next\Exception\Exceptions\InvalidArgumentException
 *             Next\DB\Driver\PDO\AbstractPDO
 *             Next\DB\Query\Renderer\MySQL
 *             PDO
 */
class SQLite extends AbstractPDO {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'dbPath' => [ 'required' => TRUE ]
    ];

    // Abstract Method Implementation

    /**
     * Get SQLite Adapter DSN
     *
     * @return string
     *  SQLite Adapter DSN used by PDO Connection
     */
    protected function getDSN() : string {
        return sprintf( 'sqlite:%s', $this -> options -> dbPath );
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity.
     * Checks if PDO SQLite Extension has been loaded
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if PDO SQLite Extension has not been loaded
     */
    public function verify() : void {

        parent::verify();

        if( ! in_array( 'sqlite', \PDO::getAvailableDrivers() ) ) {
            throw new RuntimeException( 'PDO SQLite Extension not loaded' );
        }
    }

    // Driver Interface Method Implementation

    /**
     * Get an SQL Statement Renderer
     *
     * For now there is no needs to use a SQLite-specific
     * SQL Statement Renderer Class, so let's reuse MySQL Renderer,
     * only with a different Quote Identifier Symbol.
     *
     * IMPORTANT: The Symbol CANNOT be a single quote ( ' ),
     *            Otherwise SQLite will return the column's name
     *            instead of column's value
     *
     * @return \Next\DB\Query\Renderer\Renderer
     *  MySQL Renderer Object
     */
    public function getRenderer() : Renderer {
        return new Renderer( [ 'quoteIdentifier' => '"' ] );
    }
}
