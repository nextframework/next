<?php

/**
 * Error Handler Controller Class | Exception\Handlers\Controllers\ErrorController.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception\Handlers\Controllers;

/**
 * Exception Class(es)
 */
use Next\Exception\Exception;
use Next\Exception\Exceptions\InvalidArgumentException;
use Next\Exception\Exceptions\RuntimeException;
use Next\Exception\Exceptions\AccessViolationException;

use Next\Controller\Controller;    # Abstract Controller Class
use Next\Exception\Handlers;       # Next Debug Handlers Class

/**
 * Page Controller Class to be used by our custom Error Handler
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Exception
 *             Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Exception\Exceptions\RuntimeException
 *             Next\Exception\Exceptions\AccessViolationException
 *             Next\Controller\Controller
 *             Next\Exception\Handlers
 */
class ErrorHandlerController extends Controller {

    /**
     * Collection of (funny?) messages for some of the most common Status Code Errors
     *
     * @var array $data
     */
    private $data = [

        '403' => [

            'title'    => '(403) Forbidden',
            'footnote' => 'Access Denied to <em>%1$s</em>',

            'phrases' => []
        ],

        '404' => [

            'title'    => '(404) Not Found',
            'footnote' => 'Unable to find <em>%1$s</em>',

            'phrases'  => [

                'Chuck Norris said this page doesn\'t exist and I definitely won\'t argue\n\nWill you?',

                'Sorry, we\'re f*cked!',

                'No coffee, no work ¯\_(ツ)_/¯',

                '- Where\'d it go?\n-Where\'d what go?',

                'Run Forrest! Run!',

                // — Philippe Khattou (@Phil_Khattou) August 14, 2015

                'I’m not in the office right now but if it’s important, tweet me using\n\n #YOUAREINTERRUPTINGMYVACATION',

                // The Many Faces Of (http://themanyfacesof.com)

                'Stay calm and DON\'T FREAK OUT!!!',

                // Bluegg.co.uk (http://bluegg.co.uk)

                'Ahhhhhhhhhhh! This page doesn\'t exist'
            ]
        ],

        '405' => [

            'title'    => '(405) Method Not Allowed',
            'footnote' => 'Invalid access method to <em>%1$s</em>',

            'phrases' => []
        ],

        '500' => [

            'title'    => '(500) Internal Server Error',
            'footnote' => 'Internal Error while accessing <em>%1$s</em>',

            'phrases' => []
        ],

        '503' => [

            'title'    => '(503) Service Unavailable',
            'footnote' => '<em>%1$s</em> is not available at moment',

            'phrases' => []
        ]
    ];

    /**
     * Status Code Error Action Handler
     */
    final public function status() : void {

        // Trying to render a specific Template File

        try {

            if( array_key_exists( $this -> code, $this -> data ) && count( $this -> data[ $this -> code ] ) > 0 ) {

                shuffle( $this -> data[ $this -> code ]['phrases'] );

                $this -> view -> quote = strtr(
                    array_shift( $this -> data[ $this -> code ]['phrases'] ), [ '\n' => '<br />' ]
                );

                $this -> view -> title = $this -> data[ $this -> code ]['title'];
                $this -> view -> footnote = sprintf(
                    $this -> data[ $this -> code ]['footnote'], $this -> request -> getRequestURI( FALSE )
                );

            } else {

                $this -> view -> title = NULL;
                $this -> view -> footnote = sprintf(
                    '<em>%s</em>', $this -> request -> getRequestURI( FALSE )
                );
            }

            $this -> view -> render( 'status.phtml' );

        } catch( InvalidArgumentException | RuntimeException | AccessViolationException | Exception $e ) {

            /**
             * @internal
             *
             * These are all Exceptions thrown by Next\View\ViewException,
             * but if everything else fails, the base Exception is also
             * there as fallback
             */
            Handlers::development( $e );
        }
    }

    /**
     * Regular Error Message Action Handler
     */
    final public function error() : void {
        $this -> view -> assign( 'e', $this -> e ) -> render( 'error.phtml' );
    }
}
