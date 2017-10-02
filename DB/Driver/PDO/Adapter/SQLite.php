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
 * SQLite Connection Adapter Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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

    // Abstract Methods Implementation

    /**
     * Get SQLite Adapter DSN
     *
     * @return string
     *  SQLite Adapter DSN used by PDO Connection
     */
    protected function getDSN() {
        return sprintf( 'sqlite:%s', $this -> options -> dbPath );
    }

    /**
     * Check for SQLite Adapter Requirements
     *
     * @throws \Next\DB\Driver\DriverException
     *  PDO_SQLITE Extension was not loaded
     */
    protected function checkRequirements() {

        parent::checkRequirements();

        if( ! in_array( 'sqlite', \PDO::getAvailableDrivers() ) ) {
            throw new RuntimeException( 'PDO SQLite Extension not loaded' );
        }
    }

    // Driver Interface Method Implementation

    /**
     * Set an SQL Statement Renderer
     *
     * For now there is no needs to use a SQLite-specific
     * SQL Statement Renderer Class, so let's reuse MySQL Renderer,
     * only with a different Quote Identifier Symbol.
     *
     * IMPORTANT: The Symbol CANNOT be a single quote ( ' ),
     *             Otherwise SQLite will return the column's name
     *             instead of column's value
     *
     * @return \Next\DB\Query\Renderer\Renderer
     *  MySQL Renderer Object
     */
    public function getRenderer() {
        return new Renderer( [ 'quoteIdentifier' => '"' ] );
    }
}
