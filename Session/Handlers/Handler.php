<?php

/**
 * Sessions Handlers Interface | Session\Handlers\Handler.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Session\Handlers;

/**
 * An Interface for all custom/adapted/improved Session Handlers
 *
 * @package    Next\Session
 */
interface Handler {

    /**
     * Open Session
     *
     * @param string $savePath
     *  Session Save Path
     *
     * @param string $name
     *  Session Name
     */
    public function open( $savePath, $name ) : bool;

    /**
     * Close Session
     */
    public function close() : bool;

    /**
     * Read Session Data
     *
     * @param string $id
     *  Session Data ID
     */
    public function read( $id ) :? string;

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
     */
    public function write( $id, $data, $expires ) : bool;

    /**
     * Destroy Session Data
     *
     * @param string $id
     *  Session Data ID
     */
    public function destroy( $id ) : bool;

    /**
     * Renew Session Handler
     *
     * Garbage Collector to delete too old Session Data
     *
     * @param integer $maxlifetime
     *  Maximum Lifetime of a Session Data
     */
    public function renew( $maxlifetime ) : bool;
}
