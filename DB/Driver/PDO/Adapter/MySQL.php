<?php

/**
 * PDO Driver Adapter: MySQL | DB\Driver\Adapter\MySQL.php
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

use Next\Components\Interfaces\Configurable;     # Configurable Interface

use Next\DB\Driver\PDO\AbstractPDO;              # PDO Abstract Class
use Next\DB\Query\Renderer\MySQL as Renderer;    # MySQL Query Renderer Class

/**
 * The PDO MySQL Connector.
 * Sets additional PDO attributes, defines the DSN PDO will use and which
 * Query Renderer to render the Query Clauses
 *
 * @package    Next\DB
 *
 * @uses       Next\Exception\Exceptions\RuntimeException
 *             Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Interfaces\Configurable
 *             Next\DB\Driver\PDO\AbstractPDO
 *             Next\DB\Query\Renderer\MySQL
 *             PDO
 */
class MySQL extends AbstractPDO implements Configurable {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        'host'     => [ 'required' => FALSE, 'default' => 'localhost' ],
        'database' => [ 'required' => FALSE, 'default' => '' ]
    ];

    // Configurable Interface method Implementation

    /**
     * Post-initialization Configuration
     */
    public function configure() : void {

        $this -> connection -> setAttribute( \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE );

        $this -> connection -> setAttribute( \PDO::MYSQL_ATTR_FOUND_ROWS, TRUE );
    }

    // Abstract Method Implementation

    /**
     * Get MySQL Adapter DSN
     *
     * @return string
     *  MySQL Adapter DSN used by PDO Connection
     */
    protected function getDSN() : string {

        return sprintf(

            'mysql:host=%s;dbname=%s',

            $this -> options -> host, $this -> options -> database
        );
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity.
     * Checks if PDO MySQL Extension has been loaded
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if PDO MySQL Extension has not been loaded
     */
    public function verify() : void {

        parent::verify();

        if( ! in_array( 'mysql', \PDO::getAvailableDrivers() ) ) {
            throw new RuntimeException( 'PDO MySQL Extension not loaded' );
        }
    }

    // Driver Interface Method Implementation

    /**
     * Get an SQL Statement Renderer
     *
     * @return \Next\DB\Query\Renderer\Renderer
     *  MySQL Renderer Object
     */
    public function getRenderer() : Renderer {
        return new Renderer( [ 'quoteIdentifier' => '`' ] );
    }
}
