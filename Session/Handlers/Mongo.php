<?php

/**
 * Sessions Mongo DB Handler Class | Session\Handlers\Mongo.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Session\Handlers;

use Next\Components\Object;    # Object Class

/**
 * Mongo Handler for Session Storage Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Mongo extends Object implements Handler {

    /**
     * Session Handler Mongo DB Default Options
     *
     * @var array $defaultOptions
     */
    protected $defaultOptions = array(
        'database'  => 'Mongo',
        'savePath'  => 'mongodb://localhost:27017'
    );

    /**
     * Mongo Object
     *
     * @var Mongo$mongo
     */
    private $mongo;

    /**
     * Session Data Storage
     *
     * @var MongoCollection $collection
     */
    private $collection;

    // Session Interface Methods

    /**
     * Open Session
     *
     * @param string $savePath
     *  Session Save Path
     *
     * @param string $name
     *  Session Name
     *
     * @return boolean
     *  Always TRUE, otherwise the Handler won't work properly
     */
    public function open( $savePath, $name ) {

        $this -> mongo = new \Mongo( $savePath );

        $this -> collection = $this -> mongo -> selectCollection(

            $this -> options -> database, $name
        );

        return TRUE;
    }

    /**
     * Close Session
     *
     * @return boolean
     *  TRUE on success and FALSE on failure
     */
    public function close() {

        $this -> collection = NULL;

        return $this -> mongo -> close();
    }

    /**
     * Read Session Data
     *
     * @param string $id
     *  Session Data ID
     *
     * @return string|NULL
     *
     * If the fetching process was successfully completed and read data
     * is not corrupted, a string will be returned.
     *
     * Otherwise, NULL will
     */
    public function read( $id ) {

        $data = $this -> collection -> findOne(

            array( '_id' => $id ),

            array( 'serialized' )
        );

        if ( isset( $data['serialized'] ) && ( $data['serialized'] instanceof \MongoBinData ) ) {

            return gzuncompress( $data[ 'serialized' ] -> bin );
        }

        return NULL;
    }

    /**
     * Write Session Data
     *
     * @param string $id
     *  Session Data ID
     *
     * @param string $data
     *  Data to Store
     *
     * @param integer $expires
     *  Expiration Timestamp
     *
     * @return boolean
     *  TRUE on success and FALSE on failure
     */
    public function write( $id, $data, $expires ) {

        if( ! empty( $_SESSION ) ) {

            $this -> collection -> save(

                array(

                    '_id' => $id,

                    'data' => eval(

                        sprintf( 'return %s;', preg_replace( array( '/\w+::__set_state\(/', '/\)\)/' ),

                        array( NULL , ')' ), var_export( $_SESSION , TRUE ) )
                    )
                ),

                'serialized' => new \MongoBinData(

                    gzcompress( $data ) ),

                    'expires' => new \MongoDate( time() + $expires )
                )
            );
        }

        return TRUE;
    }

    /**
     * Destroy Session Data
     *
     * @param string $id
     *  Session Data ID
     *
     * @return boolean
     *  TRUE on success and FALSE on failure
     */
    public function destroy( $id ) {
        return $this -> collection -> remove( array( '_id' => $id ) );
    }

    /**
     * Renew Session Handler
     *
     * Garbage Collector to delete too old Session Data
     *
     * @param integer $maxlifetime
     *  Maximum Lifetime of a Session Data
     *
     * @return boolean
     *  TRUE on success and FALSE on failure
     */
    public function renew( $maxlifetime ) {

        $expireTime = time() - $maxlifetime;

        return $this -> collection
                     -> remove(

                            array(

                                'expires' => array(

                                    '$lte' => new \MongoDate( $expireTime )
                                )
                            )
                        );
    }
}
