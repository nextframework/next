<?php

namespace Next\DB\Driver\PDO\Adapter;

use Next\DB\Driver\DriverException;    # Driver Exception Class
use Next\DB\Driver\PDO\AbstractPDO;    # PDO Abstract Class

/**
 * MySQL Connection Adapter Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class MySQL extends AbstractPDO {

    // Abstract Methods Implementation

    /**
     * MySQL Driver extra initialization
     */
    protected function configure() {

        $this -> connection -> setAttribute( \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE );

        $this -> connection -> setAttribute( \PDO::MYSQL_ATTR_FOUND_ROWS, TRUE );
    }

    /**
     * Get MySQL Adapter DSN
     *
     * @return string
     *  MySQL Adapter DSN used by PDO Connection
     *
     * @throws Next\DB\Driver\DriverException
     *  Required <strong>Host</strong> or <strong>Database</strong>
     *  parameters was not set in Connection Parameters
     */
    protected function getDSN() {

        if( ! isset( $this -> options -> host ) || empty( $this -> options -> host ) ) {

            throw DriverException::missingConnectionAdapterParameter(

                'Missing DSN Host for MySQL Adapter'
            );
        }

        if( ! isset( $this -> options -> database ) || empty( $this -> options -> database ) ) {

            throw DriverException::missingConnectionAdapterParameter(

                'Missing DSN Database for MySQL Adapter'
            );
        }

        return sprintf( 'mysql:host=%s;dbname=%s', $this -> options -> host, $this -> options -> database );
    }

    /**
     * Check for MySQL Adapter Requirements
     *
     * @throws Next\DB\Driver\DriverException
     *  PDO_MYSQL Extension was not loaded
     */
    protected function checkRequirements() {

        // Checking for PDO Extension

        parent::checkRequirements();

        // Checking for PDO MySQL Extension

        if( ! in_array( 'mysql', \PDO::getAvailableDrivers() ) ) {

            throw DriverException::unfullfilledRequirements(

                'PDO MySQL Driver was not loaded'
            );
        }
    }

    // Interface Method Implementation

    /**
     * Set an SQL Statement Renderer
     *
     * @return Next\DB\Query\Renderer\Renderer
     *  MySQL Renderer Object
     */
    public function getRenderer() {
        return new \Next\DB\Query\Renderer\MySQL( "`" );
    }
}
