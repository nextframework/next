<?php

/**
 * HTTP Request Browser Informations Class | HTTP\Request\Browser.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Request;

use Next\Components\Object;    # Object Class

/**
 * Defines a class to obtain various informations by analyzing Request and Server variables
 *
 * @package    Next\Request
 *
 * @todo       Needs a SERIOUS rewriting >.<
 */
class Browser extends Object {

    /**
     * IP Address Regular Expression
     *
     * @var string
     */
    const IP_REGEX = '/\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/';

    /**
     * Unknown Browser/Platform
     *
     * @var string
     */
    const UNKNOWN               = 'unknown';

    /**
     * Opera
     *
     * @var string
     */
    const OPERA                 = 'Opera';

    /**
     * Opera Mini
     *
     * @var string
     */
    const OPERA_MINI            = 'Opera Mini';

    /**
     * WebTV
     *
     * @var string
     */
    const WEBTV                 = 'WebTV';

    /**
     * Internet Explorer
     *
     * @var string
     */
    const IE                    = 'Internet Explorer';

    /**
     * Pocket Internet Explorer
     *
     * @var string
     */
    const POCKET_IE             = 'Pocket Internet Explorer';

    /**
     * Konqueror
     *
     * @var string
     */
    const KONQUEROR             = 'Konqueror';

    /**
     * iCab
     *
     * @var string
     */
    const ICAB                  = 'iCab';

    /**
     * OmniWeb
     *
     * @var string
     */
    const OMNIWEB               = 'OmniWeb';

    /**
     * Firebird
     *
     * @var string
     */
    const FIREBIRD              = 'Firebird';

    /**
     * Firefox
     *
     * @var string
     */
    const FIREFOX               = 'Firefox';

    /**
     * Iceweasel
     *
     * @var string
     */
    const ICEWEASEL             = 'Iceweasel';

    /**
     * Shiretoko
     *
     * @var string
     */
    const SHIRETOKO             = 'Shiretoko';

    /**
     * Mozilla
     *
     * @var string
     */
    const MOZILLA               = 'Mozilla';

    /**
     * Amaya
     *
     * @var string
     */
    const AMAYA                 = 'Amaya';

    /**
     * Lynx
     *
     * @var string
     */
    const LYNX                  = 'Lynx';

    /**
     * Safari
     *
     * @var string
     */
    const SAFARI                = 'Safari';

    /**
     * iPhone
     *
     * @var string
     */
    const IPHONE                = 'iPhone';

    /**
     * iPod
     *
     * @var string
     */
    const IPOD                  = 'iPod';

    /**
     * iPad
     *
     * @var string
     */
    const IPAD                  = 'iPad';

    /**
     * Chrome
     *
     * @var string
     */
    const CHROME                = 'Chrome';

    /**
     * Android
     *
     * @var string
     */
    const ANDROID               = 'Android';

    /**
     * GoogleBot
     *
     * @var string
     */
    const GOOGLEBOT             = 'GoogleBot';

    /**
     * Yahoo! Slurp
     *
     * @var string
     */
    const SLURP                 = 'Yahoo! Slurp';

    /**
     * W3C Validator
     *
     * @var string
     */
    const W3CVALIDATOR          = 'W3C Validator';

    /**
     * BlackBerry
     *
     * @var string
     */
    const BLACKBERRY            = 'BlackBerry';

    /**
     * IceCat
     *
     * @var string
     */
    const ICECAT                = 'IceCat';

    /**
     * Nokia S60 OSS Browser
     *
     * @var string
     */
    const NOKIA_S60             = 'Nokia S60 OSS Browser';

    /**
     * Nokia Browser
     *
     * @var string
     */
    const NOKIA                 = 'Nokia Browser';

    /**
     * MSN Browser
     *
     * @var string
     */
    const MSN                   = 'MSN Browser';

    /**
     * MSN Bot
     *
     * @var string
     */
    const MSNBOT                = 'MSN Bot';

    /**
     * Netscape Navigator
     *
     * @var string
     */
    const NETSCAPE_NAVIGATOR    = 'Netscape Navigator';

    /**
     * Galeon
     *
     * @var string
     */
    const GALEON                = 'Galeon';

    /**
     * NetPositive
     *
     * @var string
     */
    const NETPOSITIVE           = 'NetPositive';

    /**
     * Phoenix
     *
     * @var string
     */
    const PHOENIX               = 'Phoenix';

    // Platforms

    /**
     * Windows
     *
     * @var string
     */
    const WINDOWS               = 'Windows';

    /**
     * Windows CE
     *
     * @var string
     */
    const WINDOWS_CE            = 'Windows CE';

    /**
     * Apple
     *
     * @var string
     */
    const APPLE                 = 'Apple';

    /**
     * Linux
     *
     * @var string
     */
    const LINUX                 = 'Linux';

    /**
     * OS/2
     *
     * @var string
     */
    const OS2                   = 'OS/2';

    /**
     * BeOS
     *
     * @var string
     */
    const BEOS                  = 'BeOS';

    /**
     * FreeBSD
     *
     * @var string
     */
    const FREEBSD               = 'FreeBSD';

    /**
     * OpenBSD
     *
     * @var string
     */
    const OPENBSD               = 'OpenBSD';

    /**
     * NetBSD
     *
     * @var string
     */
    const NETBSD                = 'NetBSD';

    /**
     * SunOS
     *
     * @var string
     */
    const SUNOS                 = 'SunOS';

    /**
     * OpenSolaris
     *
     * @var string
     */
    const OPENSOLARIS           = 'OpenSolaris';

    /**
     * Found informations
     *
     * @var stdClass $info
     */
    private $info;

    /**
     * Additional Initialization
     */
    public function init() {

        $this -> info = new \stdClass;

        $this  ->  reset();
    }

    // Accessors

    /**
     * Get ALL Detected Information
     *
     * @return \Next\HTTP\Request\Browser
     *  Browser Object (Information are stored in a stdClass)
     */
    public function getInfo() {

        // We'll detect information just when requested

        $this  -> detectIP();

        $this  -> detectPlatform();

        $this  -> detectBrowser();

        return $this -> info;
    }

    /**
     * Get detected IP Address
     *
     * @return string
     *  User IP Address
     */
    public function getIP() {

        // Detecting IP if not detected yet

        if( is_null( $this -> info -> IP ) ) {

            $this -> detectIP();
        }

        return $this -> info -> IP;
    }

    /**
     * Get detected Platform
     *
     * @return string
     *  User Platform, Operating System or Mobile Device Name
     */
    public function getPlatform() {

        // Detecting Platform if not detected yet

        if( is_null( $this -> info -> platform ) ) {

            $this -> detectPlatform();
        }

        return $this -> info -> platform;
    }

    /**
     * Get detected Browser
     *
     * @param boolean|optional $includeVersion
     *  If TRUE, detected Browser Version will be returned too and, in that case,
     *  as an array of Information
     *
     * @return array|string
     *
     *   <p>
     *       If argument is set to TRUE both browser name and browser
     *       version will be returned.
     *   </p>
     *
     *   <p>Otherwise only browser name will</p>
     */
    public function getBrowser( $includeVersion = FALSE ) {

        // Detecting Browser if not detected yet

        if( is_null( $this -> info -> browser ) || is_null( $this -> info -> version ) ) {
            $this -> detectBrowser();
        }

        return ( $includeVersion ?
            [ $this -> info -> browser, $this -> info -> version ] :
                $this -> info -> browser );
    }

    // Auxiliary Methods

    /**
     * Resets all detected Information
     */
    private function reset() {

        $this -> info -> agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : NULL );

        // Basic Information

        $this -> info -> browser     = self::UNKNOWN;
        $this -> info -> version     = self::UNKNOWN;
        $this -> info -> platform    = self::UNKNOWN;

        // Features

        $this -> info -> isMobile    = FALSE;
        $this -> info -> isRobot     = FALSE;
        $this -> info -> IP          = NULL;
    }

    /**
     * Detects User's IP
     */
    private function detectIP() {

        $headers = [

            'CLIENT_IP', 'FORWARDED', 'FORWARDED_FOR', 'FORWARDED_FOR_IP', 'HTTP_CLIENT_IP',
            'HTTP_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED_FOR_IP', 'HTTP_PC_REMOTE_ADDR',
            'HTTP_PROXY_CONNECTION', 'HTTP_VIA', 'HTTP_X_FORWARDED', 'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED_FOR_IP', 'HTTP_X_IMFORWARDS', 'HTTP_XROXY_CONNECTION', 'VIA',
            'X_FORWARDED', 'X_FORWARDED_FOR', 'REMOTE_ADDR'
        ];

        foreach( $headers as $header ) {

            $value = getenv( $header );

            if( $value !== FALSE ) {

                $value = trim( $value );

                if( preg_match( self::IP_REGEX, $value ) ) {

                    $this -> info -> IP =& $value;

                } else if( strpos( ',', $value ) !== FALSE ) {

                    $parts = explode( ',', $value );

                    $occurrence = trim( array_shift( $parts ) );

                    $portOffset = strpos( $occurrence, ':' );

                    if( $portOffset !== FALSE && (int) $portOffset != 0 ) {

                        $occurrence = substr( $occurrence, 0, (int) $portOffset );
                    }

                    if( preg_match( self::IP_REGEX, $occurrence ) != 0 ) {

                        $this -> info -> IP =& $occurrence;
                    }
                }
            }
        }
    }

    /**
     * Detects Platform
     */
    private function detectPlatform() {

        $platforms = [

            'windows'        => self::WINDOWS,       'iPad'           => self::IPAD,
            'iPod'           => self::IPOD,          'iPhone'         => self::IPHONE,
            'mac'            => self::APPLE,         'android'        => self::ANDROID,
            'linux'          => self::LINUX,         'Nokia'          => self::NOKIA,
            'BlackBerry'     => self::BLACKBERRY,    'FreeBSD'        => self::FREEBSD,
            'OpenBSD'        => self::OPENBSD,       'OpenSolaris'    => self::OPENSOLARIS,
            'SunOS'          => self::SUNOS,         'OS\/2'          => self::OS2,
            'BeOS'           => self::BEOS,          'win'            => self::WINDOWS
        ];

        foreach( $platforms as $search => $platform ) {

            if( stripos( $this -> info -> agent, $search ) !== FALSE ) {

                $this -> info -> platform =& $platform;

                break;
            }
        }
    }

    /**
     * Detects Browser
     */
    private function detectBrowser() {

        /**
         * @internal
         * Opera must be checked before FireFox due to the odd
         * user agents used in some old versions of Opera
         *
         * WebTV is strapped onto Internet Explorer so we must
         * check for WebTV before IE
         *
         * Galeon is based on Firefox and needs to be
         * tested before Firefox
         *
         * OmniWeb is based on Safari, so it needs to be checked
         * before Safari
         *
         * Netscape 9+ is based on Firefox, so it needs to be checked
         * before FireFox
         */
        $methodsIterator = new \ArrayIterator(

            [

                'WebTv', 'InternetExplorer', 'Opera', 'Galeon',
                'NetscapeNavigator', 'Firefox', 'Chrome', 'OmniWeb',


                // Mobile Devices

                'Android', 'iPad', 'iPod', 'iPhone', 'BlackBerry', 'Nokia',

                // Common Crawler Bots

                'GoogleBot', 'MSNBot', 'Slurp',

                // WebKit base check (post mobile and others)

                'Safari',

                // Not-so-common Browsers

                'NetPositive', 'Firebird', 'Konqueror', 'Icab', 'Phoenix',
                'Amaya', 'Lynx', 'Shiretoko', 'IceCat', 'W3CValidator', 'IceWeasel',

                // Mozilla is such an open standard that you must check it last

                'Mozilla'
            ]
        );

        while( $methodsIterator -> valid() ) {

            $result = (bool) call_user_func(
                [ $this, $methodsIterator -> current() ]
            );

            if( $result !== FALSE ) break;

            $methodsIterator -> next();
        }
    }

    // Browser Detection Methods

        // Common Browsers

    /**
     * WebTV
     *
     * @see http://www.webtv.net/pc
     *
     * @return boolean
     *  TRUE if is WebTV Browser and FALSE otherwise
     */
    private function WebTv() {

        $occurrence = stristr( $this -> info -> agent, 'webtv' );

        if( $occurrence !== FALSE ) {

            $occurrence = explode( '/', $occurrence );

            if( isset( $occurrence[ 1 ] ) ) {

                $this -> info -> browser = self::WEBTV;

                // Version

                $version = explode( ' ', $occurrence[ 1 ] );

                $this -> info -> version =& $version[ 0 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Internet Explorer, Pocket Internet Explorer and the odd MSN Explorer
     *
     * @see http://www.microsoft.com/ie
     *  http://en.wikipedia.org/wiki/Internet_Explorer_Mobile
     *  http://explorer.msn.com
     *
     * @return boolean
     *  TRUE if is Internet Explorer Browser and FALSE otherwise
     */
    private function InternetExplorer() {

        // Test for v1 - v1.5 IE

        if( stripos( $this -> info -> agent, 'microsoft internet explorer' ) !== FALSE ) {

            $this -> info -> browser = self::IE;

            // Version

            $version = strstr( $this -> info -> agent, '/' );

            $this -> info -> version = ( in_array( $version, [ 308, 425, 426, 474, '0b1', '0B1' ] ) ? '1.5' : '1.0' );

            return TRUE;
        }

        // Test for versions > 1.5

        else if( stripos( $this -> info -> agent, 'msie' ) !== FALSE &&
                    stripos( $this -> info -> agent, 'opera' ) === FALSE ) {

            // Check for the odd MSN Explorer

            if( stripos( $this -> info -> agent, 'msnb' ) !== FALSE ) {

                $this -> info -> browser = self::MSN;

                // Version

                $version = explode( ' ', stristr(
                    str_replace( ';', '; ', $this -> info -> agent ), 'MSN' )
                );

                if( isset( $version[ 1 ] ) ) {
                    $this -> info -> version = str_replace( [ '(', ')', ';' ], '', $version[ 1 ] );
                }

            } else {

                $version = explode( ' ', stristr(
                    str_replace( ';', '; ', $this -> info -> agent ), 'msie' )
                );

                $this -> info -> browser = self::IE;

                // Version

                if( isset( $version[ 1 ] ) ) {

                    $this -> info -> version = str_replace(
                        [ '(', ')', ';' ], '', $version[ 1 ]
                    );
                }
            }

            return TRUE;
        }

        // Test for Pocket IE

        else if( stripos( $this -> info -> agent, 'mspie' ) !== FALSE || stripos( $this -> info -> agent, 'pocket' ) !== FALSE ) {

            $occurrence = explode( ' ', stristr( $this -> info -> agent, 'mspie' ) );

            $this -> info -> browser = self::POCKET_IE;

            // Browser Platform

            $this -> info -> platform = self::WINDOWS_CE;

            // Feature: Mobile

            $this -> info -> isMobile = TRUE;

            // Version... Better repeat one condition than all the "sets" before

            if( stripos( $this -> info -> agent, 'mspie' ) !== FALSE ) {

                if( isset( $occurrence[ 1 ] ) ) {

                    $this -> info -> version = $occurrence[ 1 ];
                }

            } else {

                $version = explode( '/', $this -> info -> agent );

                if( isset( $version[ 1 ] ) ) {
                    $this -> info -> version = $version[ 1 ];
                }
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Opera and Opera Mini
     *
     * @see http://www.opera.com
     *  http://www.opera.com/mini
     *
     * @return boolean
     *  TRUE if is Opera Browser and FALSE otherwise
     */
    private function Opera() {

        $opera     = stristr( $this -> info -> agent, 'opera' );

        $operaMini = stristr( $this -> info -> agent, 'opera mini' );

        // Test for Opera Mini

        if( $operaMini !== FALSE ) {

            $this -> info -> browser  = self::OPERA_MINI;

            // Feature: Mobile

            $this -> info -> isMobile = TRUE;

            // Version

            if( strpos( $operaMini, '/' ) !== FALSE ) {

                $version = explode( '/', $operaMini );

                if( isset( $version[ 1 ] ) ) {

                    $version = explode( ' ', $version[ 1 ] );

                    $this -> info -> version = $version[ 0 ];
                }

            } else {

                $version = explode( ' ', $operaMini );

                if( isset( $version[ 1 ] ) ) {

                    $this -> info -> version = $version[ 1 ];
                }
            }

            return TRUE;
        }

        // Test for Normal Opera

        else if( $opera !== FALSE ) {

            $this -> info -> browser = self::OPERA;

            if( preg_match( '/Version\/(10.*)$/', $opera, $matches ) != 0 ) {

                $this -> info -> version = $matches[ 1 ];
            }

            else if( strpos( $opera, '/' ) !== FALSE ) {

                $version = explode( '/', str_replace( '(', ' ', $opera ) );

                if( isset( $version[ 1 ] ) ) {

                    $version = explode( ' ', $version[ 1 ] );

                    $this -> info -> version = $version[ 0 ];
                }
            }

            else {

                $version = explode( ' ', $opera );

                if( isset( $version[ 1 ] ) ) {

                    $this -> info -> version = $version[ 1 ];
                }
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Galeon
     *
     * @see http://galeon.sourceforge.net
     *
     * @return boolean
     *  TRUE if is Galeon Browser and FALSE otherwise
     */
    private function Galeon() {

        $occurrence = stristr( $this -> info -> agent, 'galeon' );

        if( $occurrence !== FALSE ) {

            $occurrence = explode( ' ', $occurrence );

            $this -> info -> browser = self::GALEON;

            $version = explode( '/', $occurrence[ 0 ] );

            if( isset( $version[ 1 ] ) ) {

                $this -> info -> version =& $version[ 1 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Netscape Navigator
     *
     * @see http://browser.netscape.com
     *
     * @return boolean
     *  TRUE if is etscape Navigator Browser and FALSE otherwise
     */
    private function NetscapeNavigator() {

        if( stripos( $this -> info -> agent, 'Firefox' ) !== FALSE ) {

            if( preg_match( '/Navigator\/([^ ]*)/i', $this -> info -> agent, $matches ) != 0 ) {

                $this -> info -> browser = self::NETSCAPE_NAVIGATOR;

                $this -> info -> version =& $matches[ 1 ];

                return TRUE;
            }

        } else {

            if( preg_match( '/Netscape6?\/([^ ]*)/i', $this -> info -> agent, $matches ) ) {

                $this -> info -> browser = self::NETSCAPE_NAVIGATOR;

                $this -> info -> version =& $matches[ 1 ];

                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Firefox
     *
     * @see http://www.mozilla.com/en-US/firefox/firefox.html
     *
     * @return boolean
     *  TRUE if is Firefox Browser and FALSE otherwise
     */
    private function Firefox() {

        if( stripos( $this -> info -> agent, 'safari' ) === FALSE ) {

            $this -> info -> browser = self::FIREFOX;

            // Version

            if( preg_match( '/Firefox[\/ \(]([^ ;\)]+)/i', $this -> info -> agent, $matches ) ) {

                $this -> info -> version =& $matches[ 1 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Google Chrome
     *
     * @see http://www.google.com/chrome
     *
     * @return boolean
     *  TRUE if is Chrome Browser and FALSE otherwise
     */
    private function Chrome() {

        $occurrence = stristr( $this -> info -> agent, 'Chrome' );

        if( $occurrence !== FALSE ) {

            $occurrence = explode( '/', $occurrence );

            $this -> info -> browser = self::CHROME;

            // Version

            if( isset( $occurrence[ 1 ] ) ) {

                $version = explode(' ', $occurrence[ 1 ] );

                $this -> info -> version = $version[ 0 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * OmniWeb
     *
     * @see http://www.omnigroup.com/applications/omniweb
     *
     * @return boolean
     *  TRUE if is OmniWeb Browser and FALSE otherwise
     */
    private function OmniWeb() {

        $occurrence = stristr( $this -> info -> agent, 'omniweb' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::OMNIWEB;

            // Version

            $version = explode( '/', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );
            }

            $this -> info -> version = $version[ 0 ];

            return TRUE;
        }

        return FALSE;
    }

    // Mobile Devices

    /**
     * Android
     *
     * @see http://www.android.com
     *
     * @return boolean
     *  TRUE if is under an Android Device and FALSE otherwise
     */
    private function Android() {

        $occurrence = stristr( $this -> info -> agent, 'Android' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::ANDROID;

            //Feature: Mobile

            $this -> info -> isMobile = TRUE;

            // Version

            $version = explode( ' ', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version = $version[ 0 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * iPad
     *
     * @see http://www.apple.com
     *
     * @return boolean
     *  TRUE if is under iPad Browser and FALSE otherwise
     */
    private function iPad() {

        if( stripos( $this -> info -> agent, 'iPad' ) !== FALSE ) {

            $this -> info -> browser = self::IPAD;

            // Feature: Mobile

            $this -> info -> isMobile = TRUE;

            // Version

            $version = explode( '/', stristr( $this -> info -> agent, 'Version' ) );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version =& $version[ 0 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * iPod
     *
     * @see http://www.apple.com
     *
     * @return boolean
     *  TRUE if is under iPod Device and FALSE otherwise
     */
    private function iPod() {

        if( stripos( $this -> info -> agent, 'iPod' ) !== FALSE ) {

            $this -> info -> browser = self::IPAD;

            // Feature: Mobile

            $this -> info -> isMobile = TRUE;

            // Version

            $version = explode( '/', stristr( $this -> info -> agent, 'Version' ) );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version =& $version[ 0 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * iPhone
     *
     * @see http://www.apple.com
     *
     * @return boolean
     *  TRUE if is under iPhone Device and FALSE otherwise
     */
    private function iPhone() {

        if( stripos( $this -> info -> agent, 'iPhone' ) !== FALSE ) {

            $this -> info -> browser = self::IPHONE;

            // Feature: Mobile

            $this -> info -> isMobile = TRUE;

            // Version

            $version = explode( '/', stristr( $this -> info -> agent, 'Version' ) );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version =& $version[ 0 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Blackberry
     *
     * @see http://www.blackberry.com
     *
     * @return boolean
     *  TRUE if is under Blackberry Device and FALSE otherwise
     */
    private function BlackBerry() {

        $occurrence = stristr( $this -> info -> agent, 'blackberry' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::BLACKBERRY;

            // Feature: Mobile

            $this -> info -> isMobile = TRUE;

            //Version

            $version = explode( '/', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version =& $version[ 0 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Nokia S60 and others Nokia-based WAP browsers
     *
     * @see http://en.wikipedia.org/wiki/Web_for_S60
     *
     * @return boolean
     *  TRUE if is Nokia Device and FALSE otherwise
     */
    private function Nokia() {

        if( preg_match( '/Nokia([^\/]+)\/([^ SP]+)/i', $this -> info -> agent, $matches ) ) {

            // Version

            $this -> info -> version =& $matches[ 2 ];

            // Feature: Mobile

            $this -> info -> isMobile = TRUE;

            // Browser Name

            if( stripos( $this -> info -> agent, 'Series60' ) !== FALSE || strpos( $this -> info -> agent, 'S60' ) !== FALSE ) {

                $this -> info -> browser = self::NOKIA_S60;

            } else {

                $this -> info -> browser = self::NOKIA;
            }

            return TRUE;
        }

        return FALSE;
    }

    // Common Crawler Bots

    /**
     * Googlebot
     *
     * @see http://en.wikipedia.org/wiki/Googlebot
     *
     * @return boolean
     *  TRUE if is the Google Crawler Bot and FALSE otherwise
     */
    private function GoogleBot() {

        $occurrence = stristr( $this -> info -> agent, 'googlebot' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::GOOGLEBOT;

            // Feature: Robot

            $this -> info -> isRobot = TRUE;

            // Version

            $version = explode( '/', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version = str_replace( ';', '', $version[ 0 ] );
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * MSNBot
     *
     * @see http://search.msn.com/msnbot.htm
     *  http://en.wikipedia.org/wiki/Msnbot (used for Bing too)
     *
     * @return boolean
     *  TRUE if is the MSN Crawler Bot and FALSE otherwise
     */
    private function MSNBot() {

        $occurrence = stristr( $this -> info -> agent, 'msnbot' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::MSNBOT;

            // Feature: Robot

            $this -> info -> isRobot = TRUE;

            // Version

            $version = explode( '/', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version = str_replace( ';', '', $version[ 0 ] );
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Yahoo! Slurp
     *
     * @see http://en.wikipedia.org/wiki/Yahoo!_Slurp
     *
     * @return boolean
     *  TRUE if is the Yahoo! Slurp Crawler Bot and FALSE otherwise
     */
    private function Slurp() {

        $occurrence = stristr( $this -> info -> agent, 'Slurp' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::SLURP;

            // Feature: Robot

            $this -> info -> isRobot = TRUE;

            // Version

            $version = explode( '/', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version =& $version[ 0 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    // WebKit base check (post mobile and others)

    /**
     * Safari
     *
     * @see http://www.apple.com
     *
     * @return boolean
     *  TRUE if is Safari Browser and FALSE otherwise
     */
    private function Safari() {

        if( stripos( $this -> info -> agent, 'Safari' ) !== FALSE &&
            stripos( $this -> info -> agent, 'iPhone' ) === FALSE && stripos( $this -> info -> agent, 'iPod' ) === FALSE ) {

            $this -> info -> browser = self::SAFARI;

            // Version

            $version = explode( '/', stristr( $this -> info -> agent, 'Version' ) );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version =& $version[ 0 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    // Not-so-common Browsers

    /**
     * NetPositive
     *
     * @see http://en.wikipedia.org/wiki/NetPositive
     *
     * @return boolean
     *  TRUE if is Net Positive Browser and FALSE otherwise
     */
    private function NetPositive() {

        $occurrence = stristr( $this -> info -> agent, 'NetPositive' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::NETPOSITIVE;

            // Version

            $version = explode( '/', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $version = explode(' ',$version[ 1 ] );

                $this -> info -> version = str_replace(
                    [ '(', ')', ';' ], '', $version[ 0 ]
                );
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Firebird
     *
     * @see http://www.ibphoenix.com
     *
     * @return boolean
     *  TRUE if is Firebird Browser and FALSE otherwise
     */
    private function Firebird() {

        $occurrence = stristr( $this -> info -> agent, 'Firebird' );

        if( $occurrence !== false ) {

            $this -> info -> browser = self::FIREBIRD;

            $version = explode( '/', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $this -> info -> version =& $version[ 1 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Konqueror
     *
     * @see http://www.konqueror.org
     *
     * @return boolean
     *  TRUE if is Konqueror Browser and FALSE otherwise
     */
    private function Konqueror() {

        $occurrence = stristr( $this -> info -> agent, 'Konqueror' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::KONQUEROR;

            $version = explode( ' ', $occurrence );

            $version = explode( '/', $version[ 0] );

            if( isset( $version[ 1 ] ) ) {

                $this -> info -> version =& $version[ 1 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * iCab
     *
     * @see http://www.icab.de
     *
     * @return boolean
     *  TRUE if is iCab Browser and FALSE otherwise
     */
    private function Icab() {

        $occurrence = stristr( str_replace( '/', ' ', $this -> info -> agent ), 'icab' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::ICAB;

            // Version

            $version = explode( ' ', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $this -> info -> version =& $version[ 1 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Phoenix
     *
     * @see http://en.wikipedia.org/wiki/History_of_Mozilla_Firefox
     *
     * @return boolean
     *  TRUE if is Phoenix Browser and FALSE otherwise
     */
    private function Phoenix() {

        $occurrence = stristr( $this -> info -> agent, 'Phoenix' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::PHOENIX;

            // Version

            $version = explode( '/', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $this -> info -> version =& $version[ 1 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Amaya
     *
     * @see http://www.w3.org/Amaya
     *
     * @return boolean
     *  TRUE if is Amaya Browser and FALSE otherwise
     */
    private function Amaya() {

        $occurrence = stristr( $this -> info -> agent, 'Amaya' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::AMAYA;

            // Version

            $version = explode( '/', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version =& $version[ 0 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Lynx
     *
     * @see http://en.wikipedia.org/wiki/Lynx
     *
     * @return boolean
     *  TRUE if is Lynx Browser and FALSE otherwise
     */
    private function Lynx() {

        $occurrence = stristr( $this -> info -> agent, 'Lynx' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::LYNX;

            // Version

            $version = explode( '/', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );
            }

            $this -> info -> version =& $version[ 0 ];

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Shiretoko
     *
     * @see http://wiki.mozilla.org/Projects/shiretoko
     *
     * @return boolean
     *  TRUE if is Shiretoko Browser and FALSE otherwise
     */
    private function Shiretoko() {

        if( stripos( $this -> info -> agent, 'Mozilla' ) !== FALSE &&
            preg_match( '/Shiretoko\/([^ ]*)/i', $this -> info -> agent, $matches ) ) {

            $this-> browser = self::SHIRETOKO;

            // Version

            $this -> info -> version =& $matches[ 1 ];

            return TRUE;
        }

        return FALSE;
    }

    /**
     * IceCat
     *
     * @see http://en.wikipedia.org/wiki/GNU_IceCat
     *
     * @return boolean
     *  TRUE if is Ice Cat Browser and FALSE otherwise
     */
    private function IceCat() {

        if( stripos( $this -> info -> agent, 'Mozilla' ) !== FALSE &&
            preg_match( '/IceCat\/([^ ]*)/i', $this -> info -> agent, $matches ) ) {

            $this -> info -> browser = self::ICECAT;

            // Version

            $this -> info -> version =& $matches[ 1 ];

            return TRUE;
        }

        return FALSE;
    }

    /**
     * W3CValidator
     *
     * @see http://validator.w3.org
     *
     * @return boolean
     *  TRUE if is W3CValidator Access and FALSE otherwise
     */
    private function W3CValidator() {

        $checkLink = stristr( $this -> info -> agent, 'W3C-checklink' );

        if( $checkLink !== FALSE ) {

            $this -> info -> browser = self::W3CVALIDATOR;

            $version = explode( '/', $checkLink );

            if( isset(  $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version =& $version[ 0 ];
            }

            return TRUE;
        }
        else if( stripos( $this -> info -> agent, 'W3C_Validator' ) !== FALSE ) {

            $this -> info -> browser = self::W3CVALIDATOR;

            // Some of the Validator versions do not delineate w/ a slash - add it back in

            $agent = str_replace( 'W3C_Validator ', 'W3C_Validator/', $this -> info -> agent );

            // Version

            $version = explode( '/', stristr( $agent, 'W3C_Validator' ) );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version =& $version[ 0 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * IceWeasel
     *
     * @see http://www.geticeweasel.org
     *
     * @return boolean
     *  TRUE if is IceWheasel Browser and FALSE otherwise
     */
    private function IceWeasel() {

        $occurrence = stristr( $this -> info -> agent, 'Iceweasel' );

        if( $occurrence !== FALSE ) {

            $this -> info -> browser = self::ICEWEASEL;

            // Version

            $version = explode( '/', $occurrence );

            if( isset( $version[ 1 ] ) ) {

                $version = explode( ' ', $version[ 1 ] );

                $this -> info -> version =& $version[ 0 ];
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Mozilla
     *
     * @see http://www.mozilla.com/en-US
     *
     * @return boolean
     *  TRUE if is generic Mozilla Browser and FALSE otherwise
     */
    private function Mozilla() {

        if( stripos( $this -> info -> agent, 'mozilla' ) !== FALSE && stripos( $this -> info -> agent, 'netscape' ) === FALSE ) {

            $this -> info -> browser = self::MOZILLA;

            // Version

            if( preg_match( '/rv:[0-9].[0-9][a-b]?/i', $this -> info -> agent ) ) {

                $version = explode( ' ', stristr( $this -> info -> agent, 'rv:' ) );

                $this -> info -> version = str_replace( 'rv:', '' , $version[ 0 ] );

                return TRUE;
            }
            elseif( preg_match( '/rv:[0-9]\.[0-9]/i', $this -> info -> agent ) ) {

                $version = explode( '', stristr( $this -> info -> agent, 'rv:' ) );

                $this -> info -> version = str_replace( 'rv:', '', $version[ 0 ] );

                return TRUE;
            }
            elseif( preg_match( '/mozilla\/([^ ]*)/i', $this -> info -> agent, $matches ) ) {

                $this -> info -> version =& $matches[ 1 ];

                return TRUE;
            }
        }

        return FALSE;
    }
}