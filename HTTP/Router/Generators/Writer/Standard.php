<?php

/**
 * Routes Generator Standard Output Writer Class (Arrays) | HTTP\Router\Generators\Writer\Standard.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Router\Generators\Writer;

use Next\Components\Object;                # Object Class
use Next\HTTP\Stream\Writer as Adapter;    # HTTP Stream Writer Class
use Next\HTTP\Stream\Adapter\Socket;       # HTTP Stream Socket Adapter

/**
 * The Standard Routes Generator Writer records all Routes Informations found in
 * an PHP-array
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Components\Object
 *             Next\HTTP\Stream\Writer
 *             Next\HTTP\Stream\Adapter\Socket
 *             Next\HTTP\Router\Generators\Writer\Writer
 */
class Standard extends Object implements Writer {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'filePath' => [ 'required' => TRUE ]
    ];

    // Output Writer Interface Methods

    /**
     * Saves found Routes to be used by Router Classes
     *
     * @param array $data
     *  Data to be written
     *
     * @return integer
     *  Number of records processed
     */
    public function save( array $data ) : int {

        set_time_limit( 0 );

        $structure = [];

        foreach( $data as $application => $controllers ) {

            foreach( $controllers as $controller => $actions ) {

                foreach( $actions as $method => $data ) {

                    foreach( $data as $d ) {

                        $structure[] = [

                            'requestMethod'    => $d['requestMethod'],
                            'application'      => $application,
                            'controller'       => $controller,
                            'method'           => $method,
                            'URI'              => $d['route'],
                            'requiredParams'   => serialize( $d['params']['required'] ),
                            'optionalParams'   => serialize( $d['params']['optional'] )
                        ];
                    }
                }
            }
        }

        $records = count( $structure );

        // Writing File

        $writer = new Adapter(
            new Socket( $this -> options -> filePath, Socket::READ_WRITE )
        );

        clearstatcache();

        $filesize = (int) @filesize( $this -> options -> filePath );

        if( $filesize > 0 ) {

            /**
             * @internal
             *
             * If current PHP file is not empty, let's include and
             * merge its content with current structure before write it down
             */
            include $this -> options -> filePath;

            $structure = array_merge( $structure, ( isset( $routes ) ? $routes : [] ) );
        }

        $writer -> write(
            "<?php\n\n\$routes = " . var_export( $structure, TRUE ) . ';'
        );

        $writer -> close();

        return $records;
    }

    /**
     * Empties the PHP-array file before record found Routes
     */
    public function reset() : void {

        $writer = new Adapter(
            new Socket( $this -> options -> filePath, Socket::TRUNCATE_WRITE )
        );

        /**
         * @internal
         *
         * We don't need to write anything, but
         * \Next\HTTP\Stream\Writer::write() must be called so
         * the 'wb' opening mode used in Next\HTTP\Stream\Adapter\Socket
         * constructor above can do its job emptying the file
         */
        $writer -> write( '' );

        $writer -> close();
    }
}