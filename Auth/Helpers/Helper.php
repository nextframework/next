<?php

/**
 * Auth Helpers Interface | Auth\Helpers\Helper.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Auth\Helpers;

/**
 * Auth Helper describe the bare minimum methods to Request an external
 * OAuth Authentication and receive an Authorization Code to, perhaps,
 * consume an API from which User Data is retrieved
 *
 * @package    Next\Auth
 */
interface Helper {

    /**
     * Generates an Authentication URL to redirects the User to the
     * Consent Screen
     */
    public function getAuthenticationURL();

    /**
     * Retrieve Access Token from provided Request Code
     */
    public function getAccessToken();

    /**
     * Get Data after receive OAuth Authorization
     */
    public function getData();

    // Accessory Methods

    /**
     * Get OAuth Provider Name.
     * Being declared 'static' dispenses a class constant that can
     * have any name to hold the OAuth Helper Provider Name
     */
    public static function getProviderName();

    /**
     * Get Client Object
     */
    public function getClient();
}