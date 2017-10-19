<?php

/**
 * Google Auth Helper Class | Auth\Helpers\Google.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Auth\Helpers;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\RuntimeException;
use Next\Exception\Exceptions\InvalidArgumentException;
use Next\Exception\Exceptions\BadMethodCallException;

use Next\Components\Interfaces\Verifiable;    # Verifiable Interface
use Next\Components\Object;                   # Object Class
use Next\Components\Utils\ArrayUtils;         # Array Utils Class
use Next\DB\Entity\Entity;                    # DB Entity Interface
use Next\Validation\Validators\URL;           # URL Validator Class

/**
 * Google Auth Helper is a simple helper to ease out a little bit
 * User Authentication through Google OAuth
 *
 * @package    Next\Auth
 *
 * @uses       Next\Exception\Exceptions\RuntimeException,
 *             Next\Exception\Exceptions\InvalidArgumentException,
 *             Next\Exception\Exceptions\BadMethodCallException,
 *             Next\Auth\Helpers\Helper,
 *             Next\Components\Object,
 *             Next\Components\Utils\ArrayUtils,
 *             Next\DB\Entity\Entity,
 *             Next\Validation\Validators\URL
 */
class Google extends Object implements Verifiable, Helper {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        'model'           => [ 'type' => 'Next\DB\Entity\Entity',    'required' => TRUE ],
        'credentialsFile' => [ 'required' => TRUE ],
        'clientID'        => [ 'required' => TRUE ],
        'clientSecret'    => [ 'required' => TRUE ],
        'redirectURL'     => [ 'required' => TRUE ]
    ];

    /**
     * Google Client
     *
     * @var \Google_Client $client
     */
    protected $client;

    /**
     * Google OAUth2 Service Object
     *
     * @var \Google_Service_Oauth2 $service
     */
    protected $service;

    /**
     * Additional Initialization.
     * Checks Helper Integrity and configures Google Client Object
     */
    protected function init() {

        $client = new \Google_Client;

        $client -> setAuthConfig( $this -> options -> credentialsFile );
        $client -> setClientId( $this -> options -> clientID );
        $client -> setClientSecret( $this -> options -> clientSecret );

        $client -> setRedirectUri( $this -> options -> redirectURL );

        $client -> setAccessType( 'offline' );
        $client -> setIncludeGrantedScopes( TRUE );   // incremental authorization
        $client -> setApprovalPrompt( 'force' );

        $client -> addScope( \Google_Service_Oauth2::USERINFO_EMAIL );

        $this -> client = $client;
    }

    /**
     * Generates an Authentication URL to redirect the User to the
     * Authentication Consent Screen
     *
     * @return string
     *  The Authentication URL
     */
    public function getAuthenticationURL() {
        return $this -> client -> createAuthUrl();
    }

    /**
     * Retrieve Access Token from provided Request Code
     *
     * @return string
     *  The Access Token
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Access Token hasn't been obtained yet -AND- Request Code is missing
     */
    public function getAccessToken() {

        if( ! isset( $this -> options -> token ) ) {

            if( ! isset( $this -> options -> requestCode ) ||
                    empty( $this -> options -> requestCode ) ) {

                throw new InvalidArgumentException(
                    'A Request Code is required in order to retrieve an Access Token'
                );
            }

            return ArrayUtils::map(
                $this -> client -> fetchAccessTokenWithAuthCode(
                    $this -> options -> requestCode
                )
            );
        }

        return ArrayUtils::map( $this -> options -> token );
    }

    /**
     * Consumes Google OAuth Service adding User's E-mail Address to
     * provided Data Model
     *
     * @return \Next\DB\Entity\Entity
     *  Provided Data Model modified with User's e-mail Address
     *
     * @throws \Next\Exception\Exception\InvalidArgumentException
     *  Thrown if an Data Model hasn't been provided -OR- if it's not a valid one
     *  In order to be valid the Data model must be an instance of \Next\DB\Entity\Entity
     *
     * @throws \Next\Exception\Exceptions\BadMethodCallException
     *  Thrown if no Access Token could be fetched because both
     *  Authentication Token -AND- Request Code have been provided
     *
     * @see Google::negotiate()
     */
    public function getData() {

        $this -> negotiate();

        $data = $this -> service -> userinfo -> get();

        /**
         * @internal
         *
         * For authentication purposes all that matters for now is
         * User's E-mail Address
         */
        $this -> options -> model -> email = $data -> email;

        return $this -> options -> model;
    }

    // Accessory Methods

    /**
     * Get OAuth Provider Name
     *
     * @return string
     *  OAUth Provider Name
     */
    public static function getProviderName() {
        return 'Google';
    }

    /**
     * Get Client Object
     *
     * @return \Google_Client
     *  Google Client Object
     */
    public function getClient() {
        return $this -> client;
    }

    // Auxiliary Methods

    /**
     * Negotiates exchanged Token, configuring the Google Client and
     * initializing the Google OAuth Service to be consumed
     *
     * @throws \Next\Exception\Exceptions\BadMethodCallException
     * Thrown if Access Token is not defined, which means this method
     * is being invoked prior to an Authentication (i.e. Google Consent Screen)
     */
    private function negotiate() {

        $token = $this -> getAccessToken();

        if( $token === NULL ) {

            throw new BadMethodCallException(
                'An Access Token is required in order to access Google OAuth Service'
            );
        }

        $this -> client -> setAccessToken( $token );

        // Refreshing Token if expired

        if( $this -> client -> isAccessTokenExpired() && array_key_exists( 'refresh_token', $token ) ) {

            $this -> client -> setAccessToken(
                $this -> client -> fetchAccessTokenWithRefreshToken( $token['refresh_token'] )
            );
        }

        $this -> service = new \Google_Service_Oauth2( $this -> client );
    }

    /**
     * Checks Parameter Options Integrity
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if 'credentialsFile' points to a non-resolvable path
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Throws if 'redirectURL' is an invalid URL (mostly likely not absolute)
     */
    public function verify() {

        if( ! stream_resolve_include_path( $this -> options -> credentialsFile ) ) {
            throw new RuntimeException( 'Credentials File not found' );
        }

        $validator = new URL(
            [ 'value' => $this -> options -> redirectURL ]
        );

        if( ! $validator -> validate() ) {
            throw new RuntimeException( 'Invalid Redirection URL' );
        }
    }
}