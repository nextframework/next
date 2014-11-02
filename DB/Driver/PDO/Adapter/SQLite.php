<?php

namespace Next\DB\Driver\PDO\Adapter;

use Next\DB\Driver\DriverException;    # Driver Exception Class
use Next\DB\Driver\PDO\AbstractPDO;    # PDO Abstract Class

/**
 * SQLite Connection Adapter Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class SQLite extends AbstractPDO {

    // Abstract Methods Implementation

    /**
     * Get SQLite Adapter DSN
     *
     * @return string
     *  SQLite Adapter DSN used by PDO Connection
     *
     * @throws Next\DB\Driver\DriverException
     *  Required <strong>Path</strong> or <strong>File</strong>
     *  parameters was not set in Connection Parameters
     */
    protected function getDSN() {

        if( ! isset( $this -> options -> dbPath ) ||
              empty( $this -> options -> dbPath ) ) {

            throw DriverException::missingConnectionAdapterParameter(

                'Missing DSN Path for SQLite Adapter'
            );
        }

        return sprintf( 'sqlite:%s', $this -> options -> dbPath );
    }

    /**
     * Check for SQLite Adapter Requirements
     *
     * @throws Next\DB\Driver\DriverException
     *  PDO_SQLITE Extension was not loaded
     */
    protected function checkRequirements() {

        // Checking for PDO Extension

        parent::checkRequirements();

        // Checking for PDO SQLite Extension

        if( ! in_array( 'sqlite', \PDO::getAvailableDrivers() ) ) {

            throw DriverException::unfullfilledRequirements(

                'PDO SQLite Driver was not loaded'
            );
        }
    }

    // Interface Method Implementation

    /**
     * Set an SQL Statement Renderer
     *
     * For now there is no needs to use a SQLite-specific SQL Statement Renderer Class,
     * so let's reuse MySQL Renderer, but we have to set the Quote Identifier Symbol, though.
     *
     * IMPORTANT: The Symbol CANNOT be a single quote ( ' ), otherwise SQLite will return
     *            the column's name instead of column's value
     *
     * @return Next\DB\Query\Renderer\Renderer
     *  MySQL Renderer (yes! MySQL Renderer, read comment above)
     */
    public function getRenderer() {
        return new \Next\DB\Query\Renderer\MySQL( '"' );
    }
}
