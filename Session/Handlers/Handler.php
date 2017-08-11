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
 * Session Handlers Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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
    public function open( $savePath, $name );

    /**
     * Close Session
     */
    public function close();

    /**
     * Read Session Data
     *
     * @param string $id
     *  Session Data ID
     */
    public function read( $id );

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
    public function write( $id, $data, $expires );

    /**
     * Destroy Session Data
     *
     * @param string $id
     *  Session Data ID
     */
    public function destroy( $id );

    /**
     * Renew Session Handler
     *
     * Garbage Collector to delete too old Session Data
     *
     * @param integer $maxlifetime
     *  Maximum Lifetime of a Session Data
     */
    public function renew( $maxlifetime );
}
