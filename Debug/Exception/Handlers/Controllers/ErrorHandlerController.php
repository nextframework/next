<?php

/**
 * Error Handler Controller Class | Debug\Exception\Handlers\ErrorController.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Debug\Exception\Handlers\Controllers;

use Next\View\ViewException;               # View Exception
use Next\Controller\AbstractController;    # Abstract Controller Class

use Next\Debug\Handlers;                   # Next Debug Handlers Class

/**
 * A \Next\Controller\Controller Class to be used by our custom Error Handler
 *
 * @package    Next\Debug
 *
 * @uses       Next\View\Exception, Next\Controller\AbstractController
 */
class ErrorHandlerController extends AbstractController {

    /**
     * Collection of (funny?) messages for some of the most common Status Code Errors
     *
     * @var array $data
     */
    private $data = array(

        '403' => array(

            'title'    => '(403) Forbidden',
            'footnote' => 'Access Denied to <em>%1$s</em>',

            'phrases' => array()
        ),

        '404' => array(

            'title'    => '(404) Not Found',
            'footnote' => 'Unable to find <em>%1$s</em>',

            'phrases'  => array(

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
            )
        ),

        '405' => array(

            'title'    => '(405) Method Not Allowed',
            'footnote' => 'Invalid access method to <em>%1$s</em>',

            'phrases' => array()
        ),

        '500' => array(

            'title'    => '(500) Internal Server Error',
            'footnote' => 'Internal Error while accessing <em>%1$s</em>',

            'phrases' => array()
        ),

        '503' => array(

            'title'    => '(503) Service Unavailable',
            'footnote' => '<em>%1$s</em> is not available at moment',

            'phrases' => array()
        )
    );

    /**
     * Status Code Error Action Handler
     */
    final public function status() {

        // Trying to render a specific Template File

        try {

            if( array_key_exists( $this -> code, $this -> data ) && count( $this -> data[ $this -> code ] ) > 0 ) {

                shuffle( $this -> data[ $this -> code ]['phrases'] );

                $this -> view -> quote = str_replace(
                    '\n', '<br />', array_shift( $this -> data[ $this -> code ]['phrases'] )
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

        } catch( ViewException $e ) {

            Handlers::development( $e );
        }
    }

    /**
     * Regular Error Message Action Handler
     */
    final public function error() {

        $this -> view -> assign( 'e', $this -> e ) -> render( 'error.phtml' );
    }
}
