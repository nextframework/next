<?php

namespace Next\Components\Exception;

use Next\Exception;    # Exception Class

class UnexpectedValueException extends Exception {

    public static function extra( $option ) {

        return new self(

            sprintf(
                'Unknown Parameter Option <strong>%s</strong> defined', $option
            )
        );
    }

    public static function rethrow( Exception $e ) {
        return new self( $e -> getMessage() );
    }
}