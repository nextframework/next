<?php

/**
 * HTTP Response Header Field Validator Class: Location | Validate\Headers\Response\Location.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Validate\HTTP\Headers\Response;

use Next\Validate\HTTP\Headers\Headers;    # HTTP Protocol Headers Interface
use Next\Components\Object;                # Object Class

/**
 * RFC 2616 Location Header Validation Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Location extends Object implements Headers {

    /**
     * Validates Location Header Field in according to RFC 2616 Section 14.30
     *
     * <p><strong>RFC Specification</strong></p>
     *
     * <code>
     *        Location       = "Location" ":" absoluteURI
     * </code>
     *
     * <p><strong>Absolute URI Definition</strong></p>
     *
     * <p>
     *     HTTP or FTP Protocols, with or without SSL Character followed by
     *     <strong>://</strong> (everything optional).
     * </p>
     *
     * <p>
     *     Then followed by one or more alphanumeric characters and/or
     *     special chars
     * </p>
     *
     * <p>Special Chars are: :.?+=&%@!\/-</p>
     *
     * @return boolean
     *  TRUE if valid and FALSE otherwise
     *
     * @link
     *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.30
     *  RFC 2616 Section 14.30
     */
    public function validate() {

        $test = preg_match(

            '/^(?:(?:(?:(?:http|ftp)s?):\/\/)?[\w#:.?+=&%@!\/-]+)$/',

            $this -> options -> value
        );

        return ( $test != 0 );
    }
}
